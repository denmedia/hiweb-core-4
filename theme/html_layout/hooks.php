<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 09:55
	 */

	//Remove Emoji
	use theme\html_layout\tags\head;


	if( !head::$use_emoji ){
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}

	//Remove Meta Generator
	if( !head::$show_meta_generator ){
		//add_filter( 'the_generator', '__return_false' ); //?
		remove_action( 'wp_head', 'wp_generator' );
	}

	if( !head::$show_link_wlwmanifest ){
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}

	if( !head::$show_link_rel_EditURI ){
		remove_action( 'wp_head', 'rsd_link' );
	}

	if( !head::$show_RSS_links ){
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}

	if( !head::$show_restApi_link ){
		remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	}

	if( !head::$use_wp_embed ){
		add_action( 'wp_footer', function(){
			wp_deregister_script( 'wp-embed' );
		} );
	}

	add_action( 'wp_head', function(){
		if( is_array( head::$code ) )
			foreach( head::$code as $code ){
				echo $code . "\r\n";
			}
	} );