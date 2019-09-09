<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 01/11/2018
	 * Time: 09:47
	 */

	namespace hiweb\core\cache;


	class Cache{

		/** @var string */
		static private $default_group = 'hiweb_cache';
		/** @var Group[] */
		static $caches = [];


		/**
		 * @param null $group
		 * @return Group
		 */
		static private function get_group( $group = null ){
			if( !is_string( $group ) ) $group = self::$default_group;
			if( !array_key_exists( $group, self::$caches ) ){
				self::$caches[ $group ] = new Group( $group );
			}
			return self::$caches[ $group ];
		}


		/**
		 * @param             $key
		 * @param null|string $group
		 * @return array|mixed
		 */
		static function get( $key = null, $group = null ){
			return self::get_group( $group )->get( $key );
		}


		static function get_group_data( $group = null ){
			return self::get_group( $group )->get();
		}


		/**
		 * @param      $key
		 * @param null $value
		 * @param null $group
		 * @return bool|null
		 */
		static function set( $key, $value = null, $group = null ){
			return self::get_group( $group )->set( $key, $value );
		}


		/**
		 * @param      $key
		 * @param null $group
		 * @return bool
		 */
		static function clear( $key, $group = null ){
			return self::get_group( $group )->clear( $key );
		}


		/**
		 * @param null $group
		 * @return bool
		 */
		static function clear_group( $group = null ){
			return self::get_group( $group )->clear();
		}


		/**
		 * @param      $key
		 * @param bool $use_alive
		 * @param null $group
		 * @return bool|null
		 */
		static function is_exists( $key, $use_alive = true, $group = null ){
			return self::get_group( $group )->is_exists( $key, $use_alive );
		}

	}