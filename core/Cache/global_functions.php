<?php

	use hiweb\core\Cache\Cache;
	use hiweb\core\Cache\CacheFactory;


	if( !function_exists( 'get_cache' ) ){

		/**
		 * @param string $variable_name
		 * @param        $group_name
		 * @param        $value
		 * @return Cache
		 */
		function get_cache( $value = null, $variable_name = '', $group_name = null ){
			return CacheFactory::get( $value, $variable_name, $group_name );
		}
	}