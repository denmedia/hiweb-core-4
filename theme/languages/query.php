<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-10
	 * Time: 17:39
	 */

	namespace theme\languages;


	use theme\languages;


	class query{

		static $wp_query_key_filter_enable = 'hiweb_theme_language_filter';


		/**
		 * @param \WP_Query   $wp_query
		 * @param null|string $lang_id = ru, en...
		 */
		static public function filter( &$wp_query, $lang_id = null ){
			if( $wp_query instanceof \WP_Query ){

				///exclude post types
				if( $wp_query->get( 'post_type' ) != '' && !languages::is_post_type_allowed( $wp_query->get( 'post_type' ) ) ) return;

				if( !is_string( $lang_id ) || !languages::is_exists( $lang_id ) ) $lang_id = languages::get_current_id();
				$lang = languages::get_language( $lang_id );
				if( !$lang->is_default() ){
					$meta_query = array_key_exists( 'meta_query', $wp_query->query_vars ) ? $wp_query->query_vars['meta_query'] : [];
					$meta_query[] = [
						'key' => languages::$post_meta_key_lang_id,
						'value' => $lang_id
					];
					$wp_query->set( 'meta_query', $meta_query);
				} else {
					$meta_query = array_key_exists( 'meta_query', $wp_query->query_vars ) ? $wp_query->query_vars['meta_query'] : [];
					$meta_query[] =  [
						'relation' => 'OR',
						[
							'key' => languages::$post_meta_key_lang_id,
							'compare' => 'NOT EXISTS'
						],
						[
							'key' => languages::$post_meta_key_lang_id,
							'value' => $lang_id
						]
					];
					$wp_query->set( 'meta_query', $meta_query );
				}
			}
		}

	}