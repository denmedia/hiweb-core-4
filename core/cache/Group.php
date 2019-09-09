<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 13.03.2018
	 * Time: 13:26
	 */

	namespace hiweb\core\cache;


	class Group{

		private $group = 'hiweb_cache';
		public $life_time = 84600;


		public function __construct( $group ){
			$this->group = $group;
		}


		/**
		 * @param      $key
		 * @param null $value
		 * @return bool|null
		 */
		public function set( $key, $value = null ){
			$R = $this->get();
			if( !is_string( $key ) ){
				console_warn( 'Попытука установить кэш без ключа' );
				return null;
			}
			$R[ $key ] = [ $value, microtime( true ) ];
			return update_option( $this->group, $R, true );
		}


		/**
		 * @param null|string $key
		 * @param null|mixed  $default
		 * @return mixed|array
		 */
		public function get( $key = null, $default = null ){
			$R = get_option( $this->group, [] );
			$R = is_array( $R ) ? $R : [];
			if( !is_string( $key ) ){
				return $R;
			} else {
				return array_key_exists( $key, $R ) ? $R[ $key ][0] : $default;
			}
		}


		/**
		 * @param      $key
		 * @param bool $use_alive
		 * @return bool|null
		 */
		public function is_exists( $key, $use_alive = true ){
			if( !is_string( $key ) ){
				console_warn( 'Попытука получить кэш без ключа' );
				return null;
			}
			$cache = $this->get();
			$exists = array_key_exists( $key, $cache );
			if( array_key_exists( $key, $cache ) && intval( $cache[ $key ][1] ) == 0 ) return false;
			return ( !$exists || !$use_alive ) ? $exists : ( microtime( true ) - intval( $cache[ $key ][1] ) < intval( $this->life_time ) );
		}


		/**
		 * @param null $key
		 * @return bool
		 */
		public function clear( $key = null ){
			if( !is_string( $key ) ){
				$R = [];
			} else {
				$R = $this->get();
				unset( $R[ $key ] );
			}
			return update_option( $this->group, $R, true );
		}

	}