<?php

	namespace hiweb\core\ArrayObject;


	use hiweb\core\ArrayObject\ArrayObject;


	class ArraysRowsFactory{

		/** @var ArrayObject[][] */
		static $ArrayObjects_queue = [];
		/** @var ArrayObject */
		static $latestCreated_ArrayObject;
		/** @var ArrayObject */
		static $current_ArrayObject;
		/** @var ArrayObject */
		static $dummy_ArrayObject;


		/**
		 * Setup Array Object to queue
		 * @param ArrayObject $ArrayObject
		 * @param string      $key
		 */
		static function setup_latest( ArrayObject $ArrayObject, $key = '' ){
			self::$latestCreated_ArrayObject = $ArrayObject;
			$object_id = spl_object_id( $ArrayObject );
			if( !array_key_exists( $object_id, self::$ArrayObjects_queue[ $key ] ) ){
				self::$ArrayObjects_queue[ $key ][ $object_id ] = $ArrayObject;
			}
		}


		/**
		 * Return latest created ArrayObject or dummy ArrayObject
		 * @return ArrayObject
		 */
		static function get_latest_created(){
			if( self::$latestCreated_ArrayObject instanceof ArrayObject ){
				return self::$latestCreated_ArrayObject;
			}
			///Return default dummy ArrayObject
			if( !self::$dummy_ArrayObject instanceof ArrayObject ) self::$dummy_ArrayObject = new ArrayObject();
			return self::$dummy_ArrayObject;
		}


		/**
		 * Return current, latest or dummy ArrayObject
		 * @return ArrayObject
		 */
		static function get_current(){
			if( !self::$current_ArrayObject instanceof ArrayObject ){
				self::$current_ArrayObject = self::get_latest_created();
			}
			return self::$current_ArrayObject;
		}

	}