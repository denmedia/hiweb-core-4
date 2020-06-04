<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-10
	 * Time: 22:56
	 */

	use theme\languages;

if(!languages\detect::is_multisite()){
	add_filter( 'home_url', function( $url, $path, $orig_scheme, $blog_id ){
		if( languages\detect::is_url_prefix() && \hiweb\components\Context::is_frontend_page() && ( $path == '' || $path == '/' ) ){
			return \hiweb\PathsFactory::root() . '/' . languages::get_current_id() . $path;
		}
		return $url;
	}, 10, 4 );

	//Front Page Id
	add_filter( 'option_page_on_front', function( $value, $option ){
		if( $option === 'page_on_front' ){
			$page_id = intval( $value );
			if( $page_id > 0 ){
				$lang_post = languages::get_post( $page_id );
				$lang_post = $lang_post->get_sibling_post( languages::get_current_id() );
				if( !$lang_post->is_default() )
					return $lang_post->get_post_id();
			}
		}
		return $value;
	}, 10, 2 );

	///POSTS PERMALINK
	add_filter( 'pre_post_link', function( $permalink, $post, $leavename ){
		if( $post instanceof WP_Post && languages::is_post_type_allowed( $post->post_type ) ){
			$lang_post = languages::get_post( $post );
			if( !$lang_post->is_default() )
				$permalink = '/' . $lang_post->get_lang_id() . $permalink;
		}
		return $permalink;
	}, 10, 3 );

	///PAGES PERMALINK
	add_filter( 'get_page_uri', function( $uri, $page ){
		if( languages::is_post_type_allowed( 'page' ) && $page instanceof WP_Post ){
			$lang_post = languages::get_post( $page );
			if( !$lang_post->is_default() )
				$uri = '/' . $lang_post->get_lang_id() . '/' . $uri;
		}
		return $uri;
	}, 10, 3 );

	add_filter( 'pre_term_link', function( $termlink, $term ){
		if( $term instanceof WP_Term && languages::is_taxonomy_allowed( $term->taxonomy ) ){
			$lang_term = languages::get_term( $term );
			if( !$lang_term->is_default() )
				$termlink = '/' . $lang_term->get_lang_id() . $termlink;
		}
		return $termlink;
	}, 10, 3 );

	///NAV MENU ITEMS LINKS
	add_filter( 'wp_get_nav_menu_items', function( $items, $menu, $args ){
		if( !is_admin() ){
			$current_lang_id = languages::get_current_id();
			foreach( $items as $index => $item ){
				$R = '';
				switch( $item->object ){
					case 'category':
						$current_term = languages::get_term( $item->object_id );
						if( $current_term->get_lang_id() != $current_lang_id && $current_term->is_sibling_lang_exists( $current_lang_id ) ){
							$R = $current_term->get_sibling_term( $current_lang_id )->get_wp_term()->name;
							$items[ $index ]->url = get_term_link( $current_term->get_sibling_term( $current_lang_id )->get_term_id() );
						} else {
							$R = $current_term->get_wp_term()->name;
							$items[ $index ]->url = get_term_link( $current_term->get_term_id() );
						}
						break;
					case 'page':
						$current_post = languages::get_post( $item->object_id );
						if( $current_post->get_lang_id() != $current_lang_id && $current_post->is_sibling_lang_exists( $current_lang_id ) ){
							$R = $current_post->get_sibling_post( $current_lang_id )->get_wp_post()->post_title;
							$items[ $index ]->url = get_permalink( $current_post->get_sibling_post( $current_lang_id )->get_post_id() );
						} else {
							$R = $current_post->get_wp_post()->post_title;
							$items[ $index ]->url = get_permalink( $current_post->get_post_id() );
						}
						break;
				}
				if( $R != '' ){
					$items[ $index ]->title = $R;
				}
			}
		}
		return $items;
	}, 10, 3 );
}