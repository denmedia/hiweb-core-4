<?php

	namespace hiweb\_js;


	use hiweb\context;
	use hiweb\js;


	function enqueue_scripts_header(){
		foreach( js::$queue as $js ){
			wp_enqueue_script( $js->handle(), $js->file()->get_url(), $js->options()->get_deeps(), filemtime( $js->file()->get_path() ), $js->options()->is_in_footer() );
		}
	}

	function enqueue_scripts_footer(){
		foreach( js::$queue as $js ){
			wp_enqueue_script( $js->handle(), $js->file()->get_url(), $js->options()->get_deeps(), filemtime( $js->file()->get_path() ), $js->options()->is_in_footer() );
		}
	}

	function enqueue_scripts_shutdown(){
		if( context::is_admin_page() || context::is_frontend_page() ){
			if( is_array( js::$queue ) ) foreach( js::$queue as $handle => $js ){
				if( $js instanceof js ){
					echo js::get_queue_html( $js );
				}
			}
		}
	}

	function script_loader( $tag, $handle, $src ){
		if( isset( js::$queue[ $handle ] ) ){
			return js::get_queue_html( js::$queue[ $handle ] );
		}
		return $tag;
	}

	//Header
	add_action( 'wp_enqueue_scripts', '\hiweb\_js\enqueue_scripts_header' );
	add_action( 'admin_enqueue_scripts', '\hiweb\_js\enqueue_scripts_header' );
	add_action( 'login_enqueue_scripts', '\hiweb\_js\enqueue_scripts_header' );
	//Footer
	add_action( 'wp_print_footer_scripts', '\hiweb\_js\enqueue_scripts_footer', 2, 9999 );
	add_action( 'admin_footer', '\hiweb\_js\enqueue_scripts_footer' );
	add_action( 'admin_print_footer_scripts', '\hiweb\_js\enqueue_scripts_footer', 2, 9999 );
	//Check if not included
	add_action( 'shutdown', '\hiweb\_js\enqueue_scripts_shutdown', 2, 9999 );
	//
	add_filter( 'script_loader_tag', '\hiweb\_js\script_loader', 10, 3 );