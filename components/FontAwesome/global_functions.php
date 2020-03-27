<?php

	use hiweb\components\FontAwesome\FontAwesome_Icon;
	use hiweb\components\FontAwesome\FontAwesomeFactory;


	if( !function_exists( 'get_fontawesome' ) ){
		/**
		 * @param string $icon_class
		 * @return FontAwesome_Icon
		 */
		function get_fontawesome( $icon_class = 'wordress' ){
			return FontAwesomeFactory::get( $icon_class );
		}
	}