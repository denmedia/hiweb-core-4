<?php

	use hiweb\components\PostType\PostType;
	use hiweb\components\PostType\PostTypeFactory;


	if( !function_exists( 'add_post_type' ) ){

		/**
		 * @param string $post_type
		 * @return PostType
		 */
		function add_post_type( $post_type = 'post' ){
			return PostTypeFactory::get( $post_type );
		}
	}