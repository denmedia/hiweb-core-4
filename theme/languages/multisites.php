<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-19
	 * Time: 11:27
	 */

	namespace theme\languages;


	use hiweb\Dump;
	use hiweb\fields\types\image\field;
	use hiweb\files;
	use hiweb\images;
	use hiweb\images\image;
	use theme\languages;
	use WP_Post;


	class multisites{

		private static $sites;
		private static $languages_by_site_id;


		/**
		 * @return array[lang_id => site_id,...]
		 */
		static function get_site_ids_by_lang_id(){
			if( !is_array( self::$sites ) ){
				self::$sites = [];
				$sites = get_sites();
				/** @var \WP_Site $site */
				foreach( $sites as $site ){
					self::$sites[ get_blog_option( $site->id, ( 'hiweb-option-' . languages::$options_page_slug . '-default-id' ) ) ] = $site->blog_id;
				}
			}
			return self::$sites;
		}


		/**
		 * @return language[]
		 */
		static function get_languages_by_site_id(){
			if( !is_array( self::$languages_by_site_id ) ){
				self::$languages_by_site_id = [];
				foreach( self::get_site_ids_by_lang_id() as $lang_id => $site_id ){
					self::$languages_by_site_id[ $site_id ] = new language( [
						'id' => get_blog_option( $site_id, 'hiweb-option-' . languages::$options_page_slug . '-default-id' ),
						'name' => get_blog_option( $site_id, 'hiweb-option-' . languages::$options_page_slug . '-default-name' ),
						'title' => get_blog_option( $site_id, 'hiweb-option-' . languages::$options_page_slug . '-default-title' ),
						'site_id' => $site_id
					] );
				}
			}
			return self::$languages_by_site_id;
		}


		/**
		 * @param $site_id
		 * @return language
		 */
		static function get_language( $site_id ){
			if( array_key_exists( $site_id, self::$languages_by_site_id ) )
				return self::$languages_by_site_id[ $site_id ]; else return new language( [] );
		}


		/**
		 * @param $site_id
		 * @return array
		 */
		static function do_migrate_options_to_site( $site_id ){
			$R = [];
			foreach( \hiweb\fields\locations\locations::$locations as $location ){
				if( isset( $location->options['admin_menus'] ) && $location->options['admin_menus']->options['menu_slug'] != languages::$options_page_slug ){
					$field = $location->_get_parent_field();
					if( !$field instanceof \hiweb\fields\types\separator\field ){
						$option_name = \hiweb\fields\forms::get_field_input_option_name( $field );
						$option_name_current = multisites::get_language( $site_id )->get_field_id( $option_name, true );
						$fields_from_to[ $option_name_current ] = $option_name;
						$value = get_blog_option( get_current_blog_id(), $option_name_current, get_blog_option( get_current_blog_id(), $option_name, null ) );
						if( !is_null( $value ) ){
							if( $field instanceof field ){
								$image_source = images::get( $value );
								if( $image_source->is_attachment_exists() ){
									//									$check_file = files::get( images::get_upload_dirs()->get_path() . '/' . $image_source->get_path_attachment_by_upload_dir() );
									$check_file = files::get( images::get_upload_path_dirs()->get_path() . '/' . $image_source->get_size_original()->basename() );
									if( $check_file->is_exists() ){
										$thumbnail_id = files::get_attachment_id_from_url( $check_file->get_url( false ) );
									} else {
										$thumbnail_id = files::upload( $image_source->get_path(), $image_source->get_size_original()->basename() );
									}
									$R[ $option_name ] = update_blog_option( $site_id, $option_name, $thumbnail_id );
								}
							} else {
								$R[ $option_name ] = update_blog_option( $site_id, $option_name, $value );
							}
						}
					}
				}
			}
			return $R;
		}


		static function do_migrate_current_users_to_site( $dest_site_id ){
			$users_current = get_users();
			$users_dest_site_by_id = [];
			/** @var \WP_User $wp_user */
			foreach( get_users( [ 'blog_id' => $dest_site_id ] ) as $wp_user ){
				$users_dest_site_by_id[ $wp_user->ID ] = $wp_user->user_email;
			}
			/** @var \WP_User $wp_user */
			foreach( $users_current as $wp_user ){
				if( !array_key_exists( $wp_user->ID, $users_dest_site_by_id ) ){
					add_user_to_blog( $dest_site_id, $wp_user->ID, $wp_user->roles[0] );
				}
			}
		}


		static function do_mirgate_current_post_to_site( $wpPostOrId, $dest_site_id ){
			if( intval( $dest_site_id ) < 1 )
				return - 1;
			$wp_post = get_post( $wpPostOrId );
			if( !$wp_post instanceof WP_post )
				return - 2;
			/// post data
			$POST_DATA = [ 'wp_post' => $wp_post ];
			/// post meta
			$POST_DATA['meta'] = [];
			foreach(get_post_meta( $wp_post->ID ) as $key => $val){
				$POST_DATA['meta'][$key] = get_post_meta($wp_post->ID, $key, true);
			};
			/// post terms
			foreach( get_object_taxonomies( $wp_post->post_type ) as $taxonomy ){
				$POST_DATA['terms'][ $taxonomy ] = wp_get_post_terms( $wp_post->ID, $taxonomy );
			}
			///POST ATTACHMENTS
			$POST_DATA['attachments'] = [];
			if( has_post_thumbnail( $wp_post ) ){
				$POST_DATA['thumbnail'][ get_post_thumbnail_id( $wp_post ) ] = get_image( get_post_thumbnail_id( $wp_post ) );
			}
			////SWITCH TO BLOG
			switch_to_blog( $dest_site_id );
			$dest_post_data_keys = [ 'comment_count', 'comment_status', 'filter', 'menu_order', 'ping_status', 'pinged', 'post_author', 'post_content', 'post_content_filtered', 'post_date', 'post_date_gmt', 'post_excerpt', 'post_mime_type', 'post_modified', 'post_modified_gmt', 'post_name'/*,'post_parent'*/, 'post_password', 'post_status', 'post_title', 'post_type', 'to_ping' ];
			$DEST_POST_DATA = [];
			foreach( $dest_post_data_keys as $key ){
				if( !property_exists( $wp_post, $key ) )
					continue;
				$DEST_POST_DATA[ $key ] = $wp_post->{$key};
			}
			$dest_wp_post = get_page_by_title( $wp_post->post_title, 'OBJECT', $wp_post->post_type );
			if( !$dest_wp_post instanceof WP_Post ){
				$dest_post_id = wp_insert_post( $DEST_POST_DATA );
				//$dest_wp_post = get_post( $dest_post_id );
			} else {
				$DEST_POST_DATA['ID'] = $dest_wp_post->ID;
				$DEST_POST_DATA['post_author'] = $dest_wp_post->post_author;
				$DEST_POST_DATA['post_parent'] = $dest_wp_post->post_parent;
				wp_update_post( $DEST_POST_DATA );
				$dest_post_id = $dest_wp_post->ID;
			}
			////POST EXISTS, FILL POST DATA
			if( is_int( $dest_post_id ) ){
				restore_current_blog();
				wp_trash_post( $wp_post->ID );
				switch_to_blog( $dest_site_id );
				///POST META
				if( is_array( $POST_DATA['meta'] ) ){
					foreach( $POST_DATA['meta'] as $key => $val ){
						update_post_meta( $dest_post_id, $key, $val );
					}
				}
				///POST THUMBNAIL
				if( is_array( $POST_DATA['thumbnail'] ) && count( $POST_DATA['thumbnail'] ) > 0 ){
					$file = reset( $POST_DATA['thumbnail'] );
					if( $file instanceof image ){
						//check file exists
						$check_file = files::get( images::get_upload_path_dirs()->get_path() . '/' . $file->get_size_original()->basename() );
						if( $check_file->is_exists() ){
							$thumbnail_id = files::get_attachment_id_from_url( $check_file->get_url( false ) );
						} else {
							$thumbnail_id = files::upload( $file->get_path(), $file->get_size_original()->basename() );
						}
						update_post_meta( $dest_post_id, '_thumbnail_id', $thumbnail_id );
					}
				}
				///FIND CONTENT IMAGES
				//				preg_match_all( '/<img [^>]*src=[\'"](?<url>[^\'"]+)[\'"][^>]*>/mi', $DEST_POST_DATA['post_content'], $urls );
				//				if( is_array( $urls['url'] ) && count( $urls['url'] ) > 0 ){
				//					foreach( $urls['url'] as $url ){
				//						$file = files::get( $url );
				//						if( $file->is_image() && $file->is_local() ){
				//							restore_current_blog();
				//							$file_attach_id = files::get_attachment_id_from_url( $file->get_path_relative(false ) );
				//							switch_to_blog($dest_site_id);
				//							$file_image = get_image( $file_attach_id );
				//							$check_file = files::get( images::get_upload_path_dirs()->get_path() . '/' . $file_image->get_size_original()->basename() );
				//							if( !$check_file->is_exists() ){
				//								$thumbnail_id = get_image( files::upload( $file->get_path(), $file->basename() ) );
				//							}
				//							if( intval( $thumbnail_id ) > 0 )
				//								$DEST_POST_DATA['post_content'] = str_replace( $url, $check_file->get_url( false ), $DEST_POST_DATA['post_content'] );
				//						}
				//					}
				//				}
				///POST TERMS
				if( is_array( $POST_DATA['terms'] ) ){
					foreach( $POST_DATA['terms'] as $taxonomy => $terms ){
						$taxonomy_object = get_taxonomy( $taxonomy );
						if( $taxonomy_object->hierarchical ){
							wp_set_post_terms( $dest_post_id, [], $taxonomy, false );
							foreach( $terms as $wp_term ){
								if( !$wp_term instanceof \WP_Term )
									continue;
								if( $wp_term->parent == 0 ){
									$wp_term_exists = get_term_by( 'name', $wp_term->name, $taxonomy, 'OBJECT' );
									if( $wp_term_exists instanceof \WP_Term ){
										wp_set_post_terms( $dest_post_id, $wp_term_exists->term_id, $taxonomy, true );
									} else {
										$wp_term_exists = wp_insert_term( $wp_term->name, $taxonomy );
										if( is_array( $wp_term_exists ) && isset( $wp_term_exists['term_id'] ) ){
											wp_set_post_terms( $dest_post_id, $wp_term_exists['term_id'], $taxonomy, true );
										}
									}
								}
							}
						} else {
							$tags = [];
							foreach( $terms as $wp_term ){
								if( !$wp_term instanceof \WP_Term )
									continue;
								$tags[] = $wp_term->name;
							}
							wp_set_post_terms( $dest_post_id, $tags, $taxonomy, false );
						}
					}
				}
				///POST ATTACHMENTS

			}
			restore_current_blog();
			return $POST_DATA;
		}

	}