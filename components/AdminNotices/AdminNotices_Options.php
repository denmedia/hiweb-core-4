<?php

	namespace hiweb\components\AdminNotices;


	use hiweb\core\Cache\Cache;
	use hiweb\core\Cache\CacheFactory;


	class AdminNotices_Options{

		private static $options_key = 'hiweb-components-adminnotices-closed';
		private static $Cache;
		static $options_close_timeout = 3600;


		/**
		 * @return Cache
		 */
		private static function Cache(){
			if( !self::$Cache instanceof Cache ){
				self::$Cache = CacheFactory::get( self::$options_key, null, [] );
				self::$Cache->Cache_File()->enable();
			}
			return self::$Cache;
		}


		/**
		 * @return array
		 */
		static function get_timestamp_by_notices(){
			$R = self::Cache()->get_value();
			if( !is_array( $R ) ) return [];
			return $R;
		}


		/**
		 * @param      $notice_id
		 * @param null $timestamp
		 * @return Cache
		 */
		static function set_close_time( $notice_id, $timestamp = null ){
			if( intval( $timestamp ) < 1 ) $timestamp = microtime( true );
			$notices = self::get_timestamp_by_notices();
			$notices[ $notice_id ] = $timestamp;
			self::Cache()->Cache_File()->set( $notices, true );
			return self::Cache()->get_value();
		}


		/**
		 * Return true if notice allow to show
		 * @param $notice_id
		 * @return bool
		 */
		static function is_show( $notice_id ){
			$notices = self::get_timestamp_by_notices();
			if( !is_array( $notices ) ) return true;
			if( array_key_exists( $notice_id, $notices ) ){
				return ($notices[ $notice_id ] + self::$options_close_timeout) < microtime( true );
			} else return true;
		}

	}