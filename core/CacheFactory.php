<?php

	namespace hiweb\core;


	use hiweb\core\Cache\Cache;


	class CacheFactory{

		/** @var array|Cache[][] */
		static $caches = [];


		/**
		 * @param $groupOrVariableName
		 * @return false|string
		 */
		static private function key_convert( $groupOrVariableName ){
			if( is_object( $groupOrVariableName ) ){
				return get_class( $groupOrVariableName ) . '-' . spl_object_id( $groupOrVariableName );
			} elseif( is_array( $groupOrVariableName ) ) {
				$R = json_encode( $groupOrVariableName );
				return strlen( $R ) < 20 ? $R : md5( $R );
			} else {
				return (string)$groupOrVariableName;
			}
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
		 * @return Cache
		 */
		static function get( $variable_name = '', $group_name = null, $valueOrCallable = null, $callableArgs = [] ){
			$variable_name = self::key_convert( $variable_name );
			$group_name = self::key_convert( $group_name );
			if( self::is_exists( $variable_name, $group_name ) ){
				return self::$caches[ $group_name ][ $variable_name ];
			} else {
				self::$caches[ $group_name ][ $variable_name ] = new Cache( $variable_name, $group_name );
				if( !is_string( $valueOrCallable ) && !is_array( $valueOrCallable ) && is_callable( $valueOrCallable ) ) self::$caches[ $group_name ][ $variable_name ]->set_callable( $valueOrCallable, $callableArgs ); elseif( !is_null( $valueOrCallable ) ) self::$caches[ $group_name ][ $variable_name ]->set( $valueOrCallable );
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
				if( !$return_values ) return self::$caches[ $group_name ]; else {
					$R = [];
					foreach( self::$caches[ $group_name ] as $key => $val ){
						$R[ $key ] = $val->get();
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
		 */
		static function remove( $variable_name = '', $group_name = null ){
			$group_name = self::key_convert( $group_name );
			$variable_name = self::key_convert( $variable_name );
			unset( self::$caches[ $group_name ][ $variable_name ] );
		}


		/**
		 * @param null|string $group_name
		 */
		static function remove_group( $group_name = null ){
			$group_name = self::key_convert( $group_name );
			unset( self::$caches[ $group_name ] );
		}

	}