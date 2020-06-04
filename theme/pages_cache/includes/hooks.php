<?php

	use theme\pages_cache;


	add_action( 'rest_api_init', function(){
		//Фоновое создание кэша
		register_rest_route( 'hiweb_theme', 'pages_cache/background', [
			'methods' => 'get',
			'callback' => function(){
				if( pages_cache\options::is_enable() && pages_cache\options::is_background_enable() ){
					pages_cache\queue::init();
					return pages_cache\queue::do_process_urls();
				}
				return false;
			}
		] );
	} );

	//После добавления / обновления записи или страницы обновлять ее кэш  принудительно и так же в родительских записях и таксономиях так же обновлять
	add_action( 'wp_insert_post', function( $post_id, $post, $update ){
		if( wp_is_post_revision( $post ) || $post->post_status != 'publish' ) return;
		$page = pages_cache\page::get_page( get_permalink( $post ) );
		$page->set_content();
		pages_cache\queue::add_url( $page->get_url(), 10 );
		$relative_urls = theme\pages_cache\tools::get_relative_urls_by_post_or_term( $post, 10, $page->get_url() );
		foreach( $relative_urls as $url => $priority ){
			pages_cache\queue::add_url( $url, $priority );
		}
	}, 999999, 3 );