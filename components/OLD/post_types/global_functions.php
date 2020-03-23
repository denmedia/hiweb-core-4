<?php

	use hiweb\components\console\Console;


	if( !function_exists( 'add_post_type' ) ){
		/**
		 * Register custome post type
		 * @param $post_type
		 * @return \hiweb\post_types\post_type
		 */
		function add_post_type( $post_type ){
			return hiweb\post_types::register( $post_type );
		}
	}