<?php
	
	namespace hiweb\components\Preloader;
	
	
	use hiweb\core\Cache\CacheFactory;
	
	
	class PreloaderFactory{
		
		static $enable = true;
		static private $preloaded_post_ids = [];
		static private $preloaded_post_meta_ids = [];
		static private $preloaded_term_ids = [];
		static private $preloaded_term_meta_ids = [];
		
		
		//
		static function preload_current_queried_object(){
			console_warn( 'preload_current_queried_object' );
			if( !self::$enable || !function_exists( 'get_queried_object' ) ) return [];
			if( get_queried_object() instanceof \WP_Term ){
				global $wp_query;
				self::batch_preload_terms( get_queried_object() );
				self::batch_preload_posts( $wp_query->posts );
			}
			elseif( get_queried_object() instanceof \WP_Post ){
				return self::batch_preload_posts( get_the_ID() );
			}
			return [];
		}
		
		
		static private function posts_to_ids( $postOrIds ){
			$post_ids = [];
			if( !is_array( $postOrIds ) ) $postOrIds = [ $postOrIds ];
			foreach( (array)$postOrIds as $postOrId ){
				if( $postOrId instanceof \WP_Post ){
					$post_ids[] = $postOrId->ID;
				}
				elseif( is_numeric( $postOrId ) ){
					$post_ids[] = $postOrId;
				}
			}
			return $post_ids;
		}
		
		
		/**
		 * Return preloaded posts + post meta + terms + terms meta
		 * @param      $postOrIds
		 * @param bool $preload_thumbnails
		 * @param bool $preload_children_posts
		 * @param bool $preload_terms
		 * @param bool $woocommerce_cache
		 * @return \stdClass
		 * @version 2.0
		 */
		static function batch_preload_posts( $postOrIds, $preload_thumbnails = true, $preload_children_posts = true, $preload_terms = true, $woocommerce_cache = true ){
			if( !self::$enable ) return [];
			///
			$post_ids = self::posts_to_ids( $postOrIds );
			$post_ids_for_preload = array_diff( $post_ids, self::$preloaded_post_ids );
			//RETURN EMPTY ARRAY
			if( !is_array( $post_ids_for_preload ) || count( $post_ids_for_preload ) == 0 ){
				return [];
			}
			self::$preloaded_post_ids = array_merge( self::$preloaded_post_ids, $post_ids_for_preload );
			////BATCH PRELOAD
			$found_post_ids = [];
			$found_post_meta_ids = [];
			$found_term_ids = [];
			$found_term_meta_ids = [];
			$found_thumbnail_ids = [];
			///
			global $wpdb, $wp_object_cache;
			/// preload posts
			$query = [ "/*" . __METHOD__ . ": preload posts */" ];
			$query[] = "SELECT posts.* FROM {$wpdb->posts} AS posts";
			$where = [];
			if( $preload_children_posts ){
				$where[] = '(posts.ID IN (' . join( ',', $post_ids_for_preload ) . ') OR posts.post_parent IN (' . join( ',', $post_ids_for_preload ) . '))';
			}
			else{
				$where[] = '(posts.ID IN (' . join( ',', $post_ids_for_preload ) . '))';
			}
			$where[] = '(posts.post_status!="inherit" OR posts.post_type="attachment")';
			$where[] = 'posts.post_type!="revision"';
			$query[] = 'WHERE ' . join( ' AND ', $where );
			$query_str = join( "\n", $query );
			$wpdb->query( $query_str );
			$R = new \stdClass();
			if( $wpdb->last_result ) foreach( $wpdb->last_result as $row ){
				$found_post_ids[] = $row->ID;
				$post = get_post( (object)$row );
				wp_cache_set( $row->ID, $post, 'posts' );
				$R->posts[ $row->ID ]['wp_post'] = $post;
				$R->posts_hierarchy[ $row->post_parent ][] = $row->ID;
				$R->post_type[ $row->post_type ][] = $row->ID;
			}
			/// preload post meta
			$query = [ "/*" . __METHOD__ . ": preload post meta */" ];
			$query[] = 'SELECT posts.ID, meta.meta_key, meta.meta_value FROM ' . $wpdb->posts . ' AS posts';
			$query[] = "LEFT JOIN {$wpdb->postmeta} AS meta ON meta.post_id=posts.ID";
			$where = [ 'posts.ID IN (' . join( ',', $found_post_ids ) . ')' ];
			$query[] = 'WHERE ' . join( ' AND ', $where );
			$query_str = join( "\n", $query );
			$wpdb->query( $query_str );
			if( $wpdb->last_result ) foreach( $wpdb->last_result as $row ){
				$current_meta = wp_cache_get( $row->ID, 'post_meta' );
				if( !is_array( $current_meta ) ) $current_meta = [];
				$current_meta[ $row->meta_key ] = [ $row->meta_value ];
				wp_cache_set( $row->ID, $current_meta, 'post_meta' );
				if( $preload_thumbnails && $row->meta_key == '_thumbnail_id' ){
					$found_thumbnail_ids[] = $row->meta_value;
					if( $row->meta_value != 0 ) $R->post_thumbnails[ $row->ID ] = $row->meta_value;
				}
				$R->posts[ $row->ID ]['post_meta'][ $row->meta_key ] = $row->meta_value;
			}
			if( $preload_terms ){
				/// preload terms
				$select_fields = [ 'posts.ID' ];
				$select_fields[] = 'relation.term_taxonomy_id';
				$select_fields[] = 'taxonomy.taxonomy';
				$select_fields[] = 'terms.name AS term_name';
				$select_fields[] = 'terms.slug AS term_slug';
				$select_fields[] = 'taxonomy.description AS term_description';
				$select_fields[] = 'taxonomy.parent AS term_parent';
				$select_fields[] = 'taxonomy.count AS term_count';
				//$select_fields[] = 'termmeta.meta_key AS term_meta_key';
				//$select_fields[] = 'termmeta.meta_value AS term_meta_value';
				$query = [ 'SELECT ' . join( ',', $select_fields ) . ' FROM ' . $wpdb->posts . ' AS posts' ];
				$query[] = "LEFT JOIN {$wpdb->term_relationships} AS relation ON relation.object_id=posts.ID";
				$query[] = "LEFT JOIN {$wpdb->terms} AS terms ON terms.term_id=relation.term_taxonomy_id";
				$query[] = "LEFT JOIN {$wpdb->term_taxonomy} AS taxonomy ON taxonomy.term_taxonomy_id=relation.term_taxonomy_id";
				//$query[] = "LEFT JOIN {$wpdb->termmeta} AS termmeta ON termmeta.term_id=terms.term_id";
				$where = [ 'posts.ID IN (' . join( ',', $found_post_ids ) . ')' ];
				$where[] = 'relation.term_taxonomy_id IS NOT NULL';
				$query[] = 'WHERE ' . join( ' AND ', $where );
				$query_str = join( "\n", $query );
				$wpdb->query( $query_str );
				if( $wpdb->last_result ){
					foreach( $wpdb->last_result as $row ){
						$term = new \stdClass();
						foreach((array)$row as $tmp_key => $tmp_val) {
							if(strpos($tmp_key, 'term_') === 0) $tmp_key = substr($tmp_key, 5);
							$term->{$tmp_key} = $tmp_val;
						}
						unset( $term->ID );
						$term->term_id = $term->term_taxonomy_id;
						$found_term_ids[ $term->term_id ][] = $row->ID;
						wp_cache_set( $term->term_id, $term, 'terms' );
						$current_relation = wp_cache_get( $row->ID, $row->taxonomy . '_relationships' );
						if( !is_array( $current_relation[ $row->ID ] ) ) $current_relation = [];
						$current_relation[] = (int)$term->term_id;
						wp_cache_set( $row->ID, $current_relation, $row->taxonomy . '_relationships' );
						$R->terms[ $term->term_id ]['term'] = $term;
						$R->relationships[ $row->taxonomy ][ $row->ID ] = $current_relation;
					}
				}
				/// preload terms meta
				$query = [ "/*" . __METHOD__ . ": preload terms meta */" ];
				$query[] = 'SELECT terms.term_id, meta.meta_key, meta.meta_value FROM ' . $wpdb->terms . ' AS terms';
				$query[] = 'LEFT JOIN ' . $wpdb->termmeta . ' AS meta ON terms.term_id=meta.term_id';
				$where = [ 'terms.term_id IN (' . join( ',', array_keys( $found_term_ids ) ) . ')' ];
				//$where[] = 'meta.meta_key IS NOT NULL';
				$query[] = 'WHERE ' . join( ' AND ', $where );
				$query_str = join( "\n", $query );
				$wpdb->query( $query_str );
				if( $wpdb->last_result ){
					foreach( $wpdb->last_result as $row ){
						$current_meta = wp_cache_get( $row->term_id, 'term_meta' );
						if( !is_array( $current_meta ) ) $current_meta = [];
						$current_meta[ $row->meta_key ] = [ $row->meta_value ];
						wp_cache_set( $row->term_id, $current_meta, 'term_meta' );
						if( $preload_thumbnails && $row->meta_key == 'thumbnail_id' ) $found_thumbnail_ids[] = $row->meta_value;
						$R->terms[ $row->term_id ]['term_meta'][ $row->meta_key ] = $row->meta_value;
					}
				}
			}
			///
			if( $preload_thumbnails && count( $found_thumbnail_ids ) ){
				self::batch_preload_posts( $found_thumbnail_ids, false, false, false );
			}
			///WooCommerce Cache
			if( $woocommerce_cache && isset( $R->relationships['product_type'] ) && is_array( $R->relationships['product_type'] ) ){
				$found_products = [];
				foreach( $R->relationships['product_type'] as $product_id => $terms ){
					$type_slug = null;
					if( is_array( $terms ) && count( $terms ) > 0 ){
						$type_id = reset( $terms );
						if( isset( $R->terms[ $type_id ]['term'] ) ) $type_slug = $R->terms[ $type_id ]['term']->term_slug;
					}
					$found_products[ $product_id ] = microtime();
					wp_cache_set( 'wc_product_' . $product_id . '_cache_prefix', $found_products[ $product_id ], 'product_' . $product_id );
					wp_cache_set( 'wc_cache_' . $found_products[ $product_id ] . '__type_' . $product_id, $type_slug, 'products' );
				}
			}
			return $R;
		}
		
		
		static function batch_preload_terms( $termsOrIds, $preload_children_terms = true, $preload_thumbnails = true ){
			if( !is_array( $termsOrIds ) ) $termsOrIds = [ $termsOrIds ];
			$term_ids = [];
			foreach( $termsOrIds as $termsOrId ){
				if( $termsOrId instanceof \WP_Term ) $term_ids[] = $termsOrId->term_id;
				if( is_numeric( $termsOrId ) ) $term_ids[] = $termsOrId;
			}
			global $wpdb;
			/// preload terms
			$found_term_ids = [];
			$select_str = [ '/* ' . __METHOD__ . ': preload terms */' ];
			$select_str[] = "SELECT * FROM {$wpdb->terms} AS terms";
			$join = [];
			$join[] = "LEFT JOIN {$wpdb->term_taxonomy} AS taxonomy ON taxonomy.term_id=terms.term_id";
			$where_str = [ "WHERE terms.term_id IN (" . join( ',', $term_ids ) . ")" ];
			if( $preload_children_terms ){
				$where_str[] = "OR taxonomy.parent IN (" . join( ',', $term_ids ) . ")";
			}
			$query_str = join( "\n", array_merge( $select_str, $join, $where_str ) );
			$wpdb->query( $query_str );
			if( $wpdb->last_result ){
				foreach( $wpdb->last_result as $row ){
					$found_term_ids[] = $row->term_id;
				}
			}
			if( count( $found_term_ids ) > 0 ){
				/// preload terms meta
				$found_thumbnail_ids = [];
				$query = [ "/*" . __METHOD__ . ": preload terms meta */" ];
				$query[] = 'SELECT terms.term_id, meta.meta_key, meta.meta_value FROM ' . $wpdb->terms . ' AS terms';
				$query[] = 'LEFT JOIN ' . $wpdb->termmeta . ' AS meta ON terms.term_id=meta.term_id';
				$where = [ 'terms.term_id IN (' . join( ',', $found_term_ids ) . ')' ];
				//$where[] = 'meta.meta_key IS NOT NULL';
				$query[] = 'WHERE ' . join( ' AND ', $where );
				$query_str = join( "\n", $query );
				$wpdb->query( $query_str );
				if( $wpdb->last_result ){
					foreach( $wpdb->last_result as $row ){
						$current_meta = wp_cache_get( $row->term_id, 'term_meta' );
						if( !is_array( $current_meta ) ) $current_meta = [];
						$current_meta[ $row->meta_key ] = [ $row->meta_value ];
						wp_cache_set( $row->term_id, $current_meta, 'term_meta' );
						if( $preload_thumbnails && $row->meta_key == 'thumbnail_id' ) $found_thumbnail_ids[] = $row->meta_value;
					}
				}
				///
				if( $preload_thumbnails && count( $found_thumbnail_ids ) ){
					self::batch_preload_posts( $found_thumbnail_ids, false, false, false );
				}
			}
		}
		
		
		/**
		 * Preload by only once DB query WP_Post + post_meta, WP_Term by post ids
		 * @param $postOrIds - [post_id1, post_id2, ...] or [WP_Post1, WP_Post2, ...]
		 * @return Preloader_Post[]
		 * @version 1.1
		 */
		static function get_posts( $postOrIds ){
			$post_ids = self::posts_to_ids( $postOrIds );
			self::batch_preload_posts( $post_ids );
			//			foreach( self::batch_preload_posts( $post_ids ) as $post_id => $post_preload_data ){
			//				CacheFactory::get( $post_id, __METHOD__, function(){
			//					return new Preloader_Post( func_get_arg( 0 ) );
			//				}, [ $post_preload_data ] );
			//			}
			//			$R = [];
			//			foreach( $post_ids as $post_id ){
			//				$R[ $post_id ] = CacheFactory::get( $post_id, __METHOD__ )->get_value();
			//			}
			//			return $R;
		}
		
		
		/**
		 * @param $post_id
		 * @return Preloader_Post
		 */
		static function get_post( $post_id ){
			if( array_key_exists( $post_id, self::get_posts( $post_id ) ) ){
				return self::get_posts( $post_id )[ $post_id ];
			}
			else{
				return CacheFactory::get( 0, __METHOD__, function(){
					return new Preloader_Post();
				} )->get_value();
			}
		}
		
	}