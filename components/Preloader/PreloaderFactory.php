<?php

	namespace hiweb\components\Preloader;


	use hiweb\core\Cache\CacheFactory;


	class PreloaderFactory{

		static private $preloaded_post_ids = [];


		static private function posts_to_ids( $postOrIds ){
			$post_ids = [];
			if( !is_array( $postOrIds ) ) $postOrIds = [ $postOrIds ];
			foreach( (array)$postOrIds as $postOrId ){
				if( $postOrId instanceof \WP_Post ){
					$post_ids[] = $postOrId->ID;
				} elseif( is_numeric( $postOrId ) ) {
					$post_ids[] = $postOrId;
				}
			}
			return $post_ids;
		}


		/**
		 * Return preloaded posts + post meta + terms + terms meta
		 * @param $postOrIds
		 * @return array
		 */
		static function batch_preload_posts( $postOrIds ){
			///
			$post_ids = self::posts_to_ids( $postOrIds );
			$post_ids_for_preload = array_diff( $post_ids, self::$preloaded_post_ids );
			//RETURN EMPTY ARRAY
			if( !is_array( $post_ids_for_preload ) || count( $post_ids_for_preload ) == 0 ){
				return [];
			}
			self::$preloaded_post_ids = array_merge( self::$preloaded_post_ids, $post_ids_for_preload );
			////BATCH PRELOAD
			global $wpdb;
			$query = [ "SELECT posts.*, meta.meta_key, meta.meta_value, children.ID AS post_children, relation.term_taxonomy_id, taxonomy.taxonomy, terms.name AS term_name, terms.slug AS term_slug, taxonomy.description AS term_description, taxonomy.parent AS term_parent, taxonomy.count AS term_count, termmeta.meta_key AS term_meta_key, termmeta.meta_value AS term_meta_value FROM {$wpdb->posts} AS posts" ];
			$query[] = "LEFT JOIN {$wpdb->postmeta} AS meta ON meta.post_id=posts.ID";
			$query[] = "LEFT JOIN {$wpdb->posts} AS children ON children.post_parent=posts.ID";
			$query[] = "LEFT JOIN {$wpdb->term_relationships} AS relation ON relation.object_id=posts.ID";
			$query[] = "LEFT JOIN {$wpdb->terms} AS terms ON terms.term_id=relation.term_taxonomy_id";
			$query[] = "LEFT JOIN {$wpdb->term_taxonomy} AS taxonomy ON taxonomy.term_taxonomy_id=relation.term_taxonomy_id";
			$query[] = "LEFT JOIN {$wpdb->termmeta} AS termmeta ON termmeta.term_id=terms.term_id";
			$query[] = "WHERE (posts.ID=" . join( ' OR posts.ID=', $post_ids_for_preload ) . ') AND posts.post_status!="inherit" AND (children.post_status!="inherit" OR children.post_status IS NULL)';
			$R = [];
			$query = join( "\n", $query );
			$wpdb->query( $query );
			if( $wpdb->last_result ) foreach( $wpdb->last_result as $row ){
				if( !array_key_exists( $row->ID, $R ) ){
					$R[ $row->ID ] = new \stdClass();
					$R[ $row->ID ]->post = $row;
					$R[ $row->ID ]->parent = $row->post_parent;
					$R[ $row->ID ]->meta = [];
					$R[ $row->ID ]->children = [];
					//taxonomies
					$R[ $row->ID ]->terms = [];
					$taxonomy_names = get_object_taxonomies( $row->post_type );
					if( is_array( $taxonomy_names ) ) foreach( $taxonomy_names as $taxonomy_name ){
						$R[ $row->ID ]->terms[ $taxonomy_name ] = [];
					}
				}
				if( !is_null( $row->meta_key ) ) $R[ $row->ID ]->meta[ $row->meta_key ] = $row->meta_value;
				if( !is_null( $row->post_children ) ){
					$R[ $row->ID ]->children[ $row->post_children ] = $row->post_children;
				}
				if( !is_null( $row->term_taxonomy_id ) ){
					if( !array_key_exists( $row->term_taxonomy_id, $R[ $row->ID ]->terms[ $row->taxonomy ] ) ){
						$R[ $row->ID ]->terms[ $row->taxonomy ][ $row->term_taxonomy_id ] = [ 'name' => $row->term_name, 'slug' => urldecode( $row->term_slug ), 'description' => $row->term_description, 'parent' => $row->term_parent, 'count' => $row->term_count, 'meta' => [] ];
					}
					if( !is_null( $row->term_meta_value ) ) $R[ $row->ID ]->terms[ $row->taxonomy ][ $row->term_taxonomy_id ]['meta'][ $row->term_meta_key ] = $row->term_meta_value;
				}
			}
			return $R;
		}


		/**
		 * Preload by only once DB query WP_Post + post_meta, WP_Term by post ids
		 * @param $postOrIds - [post_id1, post_id2, ...] or [WP_Post1, WP_Post2, ...]
		 * @return Preloader_Post[]
		 */
		static function get_posts( $postOrIds ){
			$post_ids = self::posts_to_ids( $postOrIds );
			$R = [];
			foreach( self::batch_preload_posts( $post_ids ) as $post_id => $post_preload_data ){
				$R[ $post_id ] = CacheFactory::get( $post_id, __METHOD__, function(){
					return new Preloader_Post( func_get_arg( 0 ) );
				}, [ $post_preload_data ] )->get_value();
			}
			return $R;
		}


		/**
		 * @param $post_id
		 * @return Preloader_Post
		 */
		static function get_post( $post_id ){
			if( array_key_exists( $post_id, self::get_posts( $post_id ) ) ){
				return self::get_posts( $post_id )[ $post_id ];
			} else {
				return CacheFactory::get( 0, __METHOD__, function(){
					return new Preloader_Post();
				} )->get_value();
			}
		}

	}