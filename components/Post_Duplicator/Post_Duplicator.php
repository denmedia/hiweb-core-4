<?php
	
	namespace hiweb\components\Post_Duplicator;
	
	
	use hiweb\core\hidden_methods;
	use hiweb\core\Paths\PathsFactory;
	use WP_Error;
	use WP_Post;
	
	
	class Post_Duplicator{
		
		static $new_title_append = ' (копия)';
		
		use hidden_methods;
		
		
		static function init(){
			static $is_init = false;
			if( !$is_init ){
				$is_init = true;
				add_filter( 'page_row_actions', '\hiweb\components\Post_Duplicator\Post_Duplicator::_post_row_actions', 15, 2 );
				add_filter( 'post_row_actions', '\hiweb\components\Post_Duplicator\Post_Duplicator::_post_row_actions', 15, 2 );
				add_action( 'current_screen', function(){
					//include_js( __DIR__ . '/post_duplicator.min.js', 'jquery-core' );
					if( get_current_screen()->base == 'edit' && get_current_screen()->post_type != '' && isset( $_GET['hiweb-post-duplicate'] ) ){
						if( wp_verify_nonce( $_GET['_wpnonce'], 'hiweb-post-duplicate' ) ){
							self::do_duplicate( $_GET['hiweb-post-duplicate'] );
							wp_redirect( PathsFactory::get()->url()->set_params( [ 'hiweb-post-duplicate' => null, '_wpnonce' => null, 'post_status' => 'draft' ] )->get( false ) );
							die;
						}
						else{
							add_admin_notice( 'Не удалось создать дубликат записи, так как ключ проверки не совпадает' )->options()->error();
						}
					}
				} );
			}
		}
		
		
		static function _post_row_actions( $actions, $post ){
			if( $post instanceof WP_Post ){
				$wp_post_type = get_post_type_object( $post->post_type );
				$actions['hiweb-theme-post-duplicator'] = '<a data-hiweb-duplicate="' . $post->ID . '" href="' . get_url()->set_params( [ 'hiweb-post-duplicate' => $post->ID, '_wpnonce' => wp_create_nonce( 'hiweb-post-duplicate' ) ] )->get() . '">Дублировать <b>' . $wp_post_type->labels->name_admin_bar . '</b></a>';
			}
			///REMOVE WOOCOMMERCE DUPLICATE BUTTON
			if( $post->post_type == 'product' ){
				unset( $actions['duplicate'] );
			}
			return $actions;
		}
		
		
		/**
		 * @param      $post_id
		 * @param null $force_parent_post_id
		 * @param bool $_main_proccess
		 * @return int|mixed|void|WP_Error
		 */
		static function do_duplicate( $post_id, $force_parent_post_id = null, $_main_proccess = true ){
			$source_post = get_post( $post_id );
			if( !$source_post instanceof WP_Post ) return;
			$new_post_args = [
				'post_author' => get_current_user_id(),
				//'post_date' => $source_post->post_date,
				//'post_date_gmt' => $source_post->post_date_gmt,
				'post_content' => $source_post->post_content,
				'post_title' => $source_post->post_title . self::$new_title_append,
				'post_excerpt' => $source_post->post_excerpt,
				'post_status' => $_main_proccess ? 'draft' : $source_post->post_status,
				'comment_status' => $source_post->comment_status,
				'ping_status' => $source_post->ping_status,
				'post_password' => $source_post->post_password,
				'post_name' => $source_post->post_name,
				'to_ping' => $source_post->to_ping,
				'pinged' => $source_post->pinged,
				'post_modified' => $source_post->post_modified,
				'post_modified_gmt' => $source_post->post_modified_gmt,
				'post_content_filtered' => $source_post->post_content_filtered,
				'post_parent' => intval( $force_parent_post_id ) == 0 ? $source_post->post_parent : $force_parent_post_id,
				'post_type' => $source_post->post_type,
				'post_mime_type' => $source_post->post_mime_type
			];
			$destination_post_id = wp_insert_post( $new_post_args );
			if( is_int( $destination_post_id ) ){
				$destination_post = get_post( $destination_post_id );
				///META
				$test_meta = get_post_meta( $source_post->ID );
				$post_meta = [];
				if( is_array( $test_meta ) ) foreach( $test_meta as $key => $array_val ){
					update_post_meta( $destination_post_id, $key, get_post_meta( $source_post->ID, $key, true ) );
				}
				///TAXONOMY
				foreach( get_object_taxonomies( $source_post ) as $taxonomy_name ){
					$source_terms = get_the_terms( $source_post->ID, $taxonomy_name );
					$dest_terms = [];
					if( is_array( $source_terms ) ) foreach( $source_terms as $wp_term ){
						$dest_terms[] = $wp_term->term_id;
					}
					wp_set_post_terms( $destination_post_id, $dest_terms, $taxonomy_name, true );
				}
				///CHILDREN POSTS DUPLICATE
				global $wpdb;
				$post_ids = $wpdb->get_results( "SELECT ID FROM {$wpdb->posts} WHERE post_parent='{$source_post->ID}' AND post_type!='attachment'" );
				if( is_array( $post_ids ) ) foreach( $post_ids as $child_post_raw ){
					self::do_duplicate( $child_post_raw->ID, $destination_post_id, false );
				}
				///WOOCOMMERCE RECALCULATE PRICES
				if( $_main_proccess && function_exists( 'wc_update_product_lookup_tables' ) ) wc_update_product_lookup_tables();
				///
				if( $_main_proccess ) add_admin_notice( 'Дубликат записи создан. <a href="' . get_edit_post_link( $destination_post_id ) . '">Редактировать запись "' . htmlentities( $destination_post->post_title ) . '"</a>' )->options()->success();
			}
			else{
				if( $_main_proccess ) add_admin_notice( 'Не удалось создать дубликат записи' )->options()->error();
			}
			///
			return $destination_post_id;
		}
		
	}