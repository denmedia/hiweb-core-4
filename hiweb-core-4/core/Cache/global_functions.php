<?php

	use hiweb\core\Cache\Cache;
	use hiweb\core\Cache\CacheFactory;


	if( !function_exists( 'get_cache' ) ){

		/**
		 * @param string         $variable_name
		 * @param                $group_name
		 * @param mixed|callable $valueOrCallable
		 * @param array|mixed    $callableArgs
		 * @return Cache
		 */
		function get_cache( $variable_name = '', $group_name = null, $valueOrCallable = null, $callableArgs = [] ){
			return CacheFactory::get( $variable_name, $group_name, $valueOrCallable, $callableArgs = [] );
		}
	}