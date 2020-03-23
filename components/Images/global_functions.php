<?php

	use hiweb\components\Images\Image;
	use hiweb\components\Images\Images;


	if( !function_exists( 'get_image' ) ){
		/**
		 * Return File instance by path or urls
		 * @param string $path_or_url
		 * @return Image
		 */
		function get_image( $path_or_url = '' ){
			return Images::get( $path_or_url );
		}
	}