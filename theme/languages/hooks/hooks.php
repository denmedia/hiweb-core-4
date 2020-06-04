<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-10
	 * Time: 14:23
	 */

	////POST QUERY
	use theme\html_layout\tags\head;
	use theme\languages;
	use theme\languages\query;


	if( !languages\detect::is_multisite() ){
		add_action( 'pre_get_posts', function( &$wp_query ){
			/** @var WP_Query $wp_query */
			if( !is_admin() && ( !array_key_exists( query::$wp_query_key_filter_enable, $wp_query->query_vars ) || $wp_query->query_vars[ query::$wp_query_key_filter_enable ] == true ) ){
				query::filter( $wp_query );
			}
		} );

		add_action( 'save_post', function( $post_id, $post, $update ){
			//
			if( wp_is_post_revision( $post_id ) || get_post( $post_id )->post_status != 'publish' ) return;
			//
			if( array_key_exists( languages::$post_meta_key_lang_id, $_POST ) ){
				update_post_meta( $post_id, languages::$post_meta_key_lang_id, $_POST[ languages::$post_meta_key_lang_id ] );
			}
		}, 10, 3 );

		add_action( 'current_screen', function( $current_screen ){
			if( languages::is_post_type_allowed( $current_screen->post_type ) && $current_screen->base == 'post' && $current_screen->action == 'add' ){
				if( !array_key_exists( languages::$post_create_sibling_get_key_id, $_GET ) ){
					return false;
				}
				$post = languages::get_post( $_GET[ languages::$post_create_sibling_get_key_id ] );
				if( !$post->is_exists() ){
					console_warn( 'Попытка создать локализированную версию несуществующей записи/страницы' );
					return false;
				}
				$lang_id = $_GET[ languages::$post_create_sibling_get_key_lang_id ];
				if( !languages::is_exists( $lang_id ) ){
					console_warn( 'Попытка создать локализированную версию записи/страницы в неизвестной локалии' );
					return false;
				}
				if( $post->get_sibling_post( $lang_id, true )->is_exists() ){
					wp_redirect( html_entity_decode( get_edit_post_link( $post->get_sibling_post( $lang_id, true )->get_post_id() ) ), 301, 'hiweb-theme-language' );
					return false;
				}
				///MAKE ALTER LANG
				$new_term_id = languages::do_make_sibling_post( $post->ID, $lang_id );
				if( is_int( $new_term_id ) ){
					///REDIRECT
					wp_redirect( html_entity_decode( get_edit_post_link( $new_term_id ) ), 301, 'hiweb-theme-language' );
					return true;
				} else {
					console_warn( 'Не удалось создать копию статьи/страницы' );
				}
			}
			if( languages::is_post_type_allowed( $current_screen->post_type ) && $current_screen->base == 'edit-tags' ){
				if( !array_key_exists( languages::$post_create_sibling_get_key_id, $_GET ) ){
					return false;
				}
				$lang_term = languages::get_term( $_GET[ languages::$post_create_sibling_get_key_id ] );
				if( !$lang_term->is_exists() ){
					console_warn( 'Попытка создать локализированную версию несуществующего термина' );
					return false;
				}
				//Check is lang exists
				$lang_id = $_GET[ languages::$post_create_sibling_get_key_lang_id ];
				if( !languages::is_exists( $lang_id ) ){
					console_warn( 'Попытка создать локализированную версию термина в неизвестной локалии' );
					return false;
				}
				///Redirect if term exists
				if( $lang_term->get_sibling_term( $lang_id )->is_exists() ){
					wp_redirect( html_entity_decode( get_edit_term_link( $lang_term->get_sibling_term( $lang_id )->get_term_id() ) ), 301, 'hiweb-theme-language' );
					return false;
				}
				///MAKE ALTER LANG
				$new_term_id = $lang_term->do_make_sibling( $lang_id );
				if( is_int( $new_term_id ) ){
					///REDIRECT
					wp_redirect( html_entity_decode( get_edit_term_link( $new_term_id ) ), 301, 'hiweb-theme-language' );
					return true;
				} else {
					console_warn( 'Не удалось создать копию термина' );
				}
			}
			return false;
		} );

		add_action( 'edited_term', function( $term_id, $tt_id, $taxonomy ){
			if( languages::is_taxonomy_allowed( $taxonomy ) ){
				$lang_meta_key = languages::$post_meta_key_lang_id;
				if( array_key_exists( $lang_meta_key, $_POST ) ){
					update_term_meta( $term_id, $lang_meta_key, $_POST[ $lang_meta_key ] );
				}
			}
		}, 10, 3 );
	}

	add_filter( 'wp_headers', function( $headers, $WP ){
		$headers['Content-Language'] = languages::get_current_id();
		return $headers;
	}, 10, 2 );

	add_action( 'get_header', function(){
		if( !is_admin() ){
			if( is_single() || is_page() ){
				$langs = languages::get_post( get_the_ID() )->get_sibling_posts( false );
				foreach( $langs as $lang_post ){
					head::add_html_addition( '<link rel="alternate" hreflang="' . ( $lang_post->is_default() ? 'x-default' : $lang_post->get_lang_id() ) . '" href="' . get_permalink( $lang_post->ID ) . '" />' );
				}
			} elseif( is_archive() ) {
				$langs = languages::get_term( get_queried_object_id() )->get_sibling_terms();
				foreach( $langs as $lang_term ){
					if( $lang_term instanceof WP_Term ){
						head::add_html_addition( '<link rel="alternate" hreflang="' . ( $lang_term->is_default() ? 'x-default' : $lang_term->get_lang_id() ) . '" href="' . get_term_link( $lang_term->term_id ) . '" />' );
					}
				}
			}
		}
	} );



