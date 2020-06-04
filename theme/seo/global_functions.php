<?php

	use theme\seo;


	if( !function_exists( 'get_the_h1' ) ){

		/**
		 * @return mixed|string
		 */
		function get_the_h1(){

			if( function_exists( 'get_queried_object' ) ){
				$queried_object = get_queried_object();
				if( $queried_object instanceof WP_Post ){
					if( get_field( 'enable-' . $queried_object->post_type, 'hiweb-seo-main' ) ){
						if( get_field( 'enable-custom-h1-' . $queried_object->post_type, 'hiweb-seo-main' ) ){
							if( get_field( 'seo-custom-h1', $queried_object ) != '' ){
								return get_field( 'seo-custom-h1' );
							}
						}
					}
					return get_the_title( $queried_object );
				} elseif( $queried_object instanceof WP_Post_Type ) {
					if( get_field( 'enable-' . $queried_object->name, 'hiweb-seo-main' ) ){
						$archive_title = get_field( 'archive-title-' . $queried_object->name, 'hiweb-seo-main' );
						if( $archive_title != '' ){
							return $archive_title;
						}
					}
					return post_type_archive_title( '', false );
				} elseif( $queried_object instanceof WP_Term ) {
					$term_title = get_field( 'seo-custom-h1', $queried_object );
					if( $term_title != '' ){
						return $term_title;
					}
					return single_term_title( '', false );
				} elseif( $queried_object instanceof WP_User ) {
					if( seo::is_author_enable() ){
						$h1 = get_field( 'seo-custom-h1' );
						if( $h1 != '' ){
							return $h1;
						}
					}
					return get_the_author();
				} else {
					global $wp_query;
					if( $wp_query instanceof WP_Query && $wp_query->is_search() ){
						return 'Результаты поиска';
					}
				}
			}
			///
			return get_the_title();
		}
	}

	if( !function_exists( 'the_h1' ) ){


		/**
		 * Echo current h1 for current page
		 */
		function the_h1(){
			echo get_the_h1();
		}
	}

	if( !function_exists( 'get_the_post_archive_title' ) ){

		/**
		 * Возвращает кастомный луп заголовок записи
		 * @param null $post
		 * @return mixed|string
		 */
		function get_the_post_archive_title( $post = null ){
			$post = get_post( $post );
			if( $post instanceof WP_Post && get_field( 'enable-custom-loop-title-' . $post->post_type, theme\seo::$admin_menu_main ) && get_field( 'seo-custom-loop-title', $post ) != '' ){
				$title = get_field( 'seo-custom-loop-title', $post );
			} else {
				$title = get_the_title( $post );
			}
			return apply_filters('get_the_post_archive_title', $title);
		}
	}

	if( !function_exists( 'the_post_archive_title' ) ){

		/**
		 * Выводит кастомный луп заголовок записи
		 * @param null $post
		 */
		function the_post_archive_title( $post = null ){
			echo get_the_post_archive_title( $post );
		}
	}

	if( !function_exists( 'get_the_term_archive_title' ) ){
		/**
		 * Возвращает кастомный луп заголовок для термина
		 * @param        $wp_term
		 * @param string $default
		 * @return mixed|string
		 */
		function get_the_term_archive_title( $wp_term, $default = '...' ){
			$title = $default;
			if( $wp_term instanceof WP_Term ){
				$title = get_field( 'seo-custom-loop-title', $wp_term );
				if( $title == '' ) $title = $wp_term->name;
			}
			return apply_filters( 'get_the_term_archive_title', $title, $wp_term, $default );
		}
	}

	if( !function_exists( 'the_term_archive_title' ) ){
		/**
		 * Выводит кастомный заголовок для лупа термина
		 * @param        $wp_term
		 * @param string $default
		 * @return mixed|string
		 */
		function the_term_archive_title( $wp_term, $default = '...' ){
			echo get_the_term_archive_title( $wp_term, $default );
		}
	}