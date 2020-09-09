<?php

	namespace hiweb\core\Cache;


	use hiweb\core\Paths\PathsFactory;


	class CacheFactory{

		/** @var array|Cache[][] */
		static $caches = [];
		/** @var string */
		static $cache_dir = WP_CONTENT_DIR . '/cache/hiweb';
		static $file_default_alive = 2.628e+6;
		static $cache_dir_files_limit = 99999;
		static $cache_file_alive_max_limit = 2.628e+6;
		/** @var bool Set true form disable read cache files */
		static $disable_cache_file_read = false;


		/**
		 * @param $groupOrVariableName
		 * @return false|string
		 */
		static function key_convert( $groupOrVariableName ){
			if( is_object( $groupOrVariableName ) ){
				$R = get_class( $groupOrVariableName ) . '-' . spl_object_id( $groupOrVariableName );
			}
			elseif( is_array( $groupOrVariableName ) ){
				$R = json_encode( $groupOrVariableName );
				$R = strlen( $R ) < 20 ? $R : md5( $R );
			}
			else{
				$R = (string)$groupOrVariableName;
			}
			return $R;
		}


		/**
		 * @param null $group_name
		 * @return bool
		 */
		static function is_group_exists( $group_name = null ){
			$group_name = self::key_convert( $group_name );
			return array_key_exists( $group_name, self::$caches );
		}


		/**
		 * @param string $variable_name
		 * @param null   $group_name
		 * @return bool
		 */
		static function is_exists( $variable_name = '', $group_name = null ){
			if( !self::is_group_exists( $group_name ) ) return false;
			$variable_name = self::key_convert( $variable_name );
			return array_key_exists( $variable_name, self::$caches[ $group_name ] );
		}


		/**
		 * @param string              $variable_name
		 * @param null                $group_name
		 * @param null|mixed|callable $valueOrCallable - value or anonymous function(){ return '...'; }
		 * @param array               $callableArgs
		 * @param bool                $enableFile
		 * @return Cache
		 */
		static function get( $variable_name = '', $group_name = null, $valueOrCallable = null, $callableArgs = [], $enableFile = false ){
			$variable_name = self::key_convert( $variable_name );
			$group_name = self::key_convert( $group_name );
			if( self::is_exists( $variable_name, $group_name ) ){
				return self::$caches[ $group_name ][ $variable_name ];
			}
			else{
				$Cache = new Cache( $variable_name, $group_name );
				if( !is_string( $valueOrCallable ) && !is_array( $valueOrCallable ) && is_callable( $valueOrCallable ) ){
					$Cache->Cache_CallbackValue()->set_callable( $valueOrCallable, $callableArgs );
				}
				elseif( !is_null( $valueOrCallable ) ){
					$Cache->set( $valueOrCallable );
				}
				if( $enableFile ) $Cache->Cache_File()->enable();
				self::$caches[ $group_name ][ $variable_name ] = $Cache;
			}
			return self::$caches[ $group_name ][ $variable_name ];
		}


		/**
		 * @param null $group_name
		 * @param bool $return_values
		 * @return Cache[]|mixed
		 */
		static function get_group( $group_name = null, $return_values = false ){
			$group_name = self::key_convert( $group_name );
			if( self::is_group_exists( $group_name ) ){
				if( !$return_values ) return self::$caches[ $group_name ];
				else{
					$R = [];
					foreach( self::$caches[ $group_name ] as $key => $val ){
						$R[ $key ] = $val->get_value();
					}
					return $R;
				}
			}
			return [];
		}


		/**
		 * @param null   $value
		 * @param string $variable_name
		 * @param null   $group_name
		 */
		static function set( $value = null, $variable_name = '', $group_name = null ){
			$group_name = self::key_convert( $group_name );
			$variable_name = self::key_convert( $variable_name );
			self::$caches[ $group_name ][ $variable_name ] = $value;
		}


		/**
		 * @param string      $variable_name
		 * @param null|string $group_name
		 * @version 1.1
		 */
		static function remove( $variable_name = '', $group_name = null ){
			$group_name = self::key_convert( $group_name );
			$variable_name = self::key_convert( $variable_name );
			if( array_key_exists( $group_name, self::$caches ) && array_key_exists( $variable_name, self::$caches[ $group_name ] ) ){
				@unlink( self::$caches[ $group_name ][ $variable_name ]->Cache_File()->File()->get_path() );
			}
			unset( self::$caches[ $group_name ][ $variable_name ] );
		}


		/**
		 * @param null|string $group_name
		 * @version 1.1
		 */
		static function remove_group( $group_name = null ){
			$group_name = self::key_convert( $group_name );
			unset( self::$caches[ $group_name ] );
			foreach( scandir( self::$cache_dir ) as $file_name ){
				if(preg_match('/^[\.]{1,2}/', $file_name)) continue;
				if( strpos( $file_name, $group_name ) === 0 ){
					@unlink( self::$cache_dir . '/' . $file_name );
				}
			}
		}


		/**
		 *
		 */
		static function clear_old_files(){
			$subFiles = PathsFactory::get( self::$cache_dir )->file()->get_sub_files_by_mtime( true );
			if( count( $subFiles ) > self::$cache_dir_files_limit ){
				ksort( $subFiles );
				$delta = count( $subFiles ) - self::$cache_dir_files_limit;
				while( $delta > 0 ){
					$delta --;
					preg_match( '~^[\d]+~i', key( $subFiles ), $mtime );
					$mtime = intval( reset( $mtime ) );
					if( $mtime + self::$cache_file_alive_max_limit < microtime( true ) ) unlink( array_shift( $subFiles ) );
				}
			}
		}

	}