<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 03:02
	 */
	
	use hiweb\core\Paths\PathsFactory;
	
	
	add_action( 'wp_print_styles', function(){
		if( !\theme\includes\frontend::$use_wp_block_library && \hiweb\components\Context::is_frontend_page() )
			wp_styles()->dequeue( 'wp-block-library' );
	} );

	//Remove default jQuery
	add_action( 'wp_enqueue_scripts', function(){
		if( !\theme\includes\frontend::$use_wp_jquery_core && \hiweb\components\Context::is_frontend_page() ){
			global $wp_scripts;
			$jquery_path = HIWEB_THEME_VENDORS_DIR.'/jquery3/jquery-3.3.1.min.js';
			$wp_scripts->registered['jquery-core']->src = false;
			$wp_scripts->registered['jquery-core']->deps = [ PathsFactory::get($jquery_path)->handle()];
			$wp_scripts->registered['jquery-core']->ver = filemtime($jquery_path);
			//Put To footer
			foreach($wp_scripts->registered as $handle => $js_data){
				if(!is_array($wp_scripts->registered[$handle]->extra) || count($wp_scripts->registered[$handle]->extra) == 0){
					$wp_scripts->registered[$handle]->extra = ['group' => 1];
				}

			}
		}
	} );

	add_filter( 'script_loader_tag', function( $tag, $handle, $src ){
		if(!\theme\includes\frontend::$use_wp_jquery_core && \hiweb\components\Context::is_frontend_page() && preg_match('|<script\b.*(\bdefer\b)[^>]+>|im', $tag) == 0) {
			$tag = preg_replace('|<script\b|im','<script defer',$tag);
		}
		return $tag;
	}, 99, 3 );