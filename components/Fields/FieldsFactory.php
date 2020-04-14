<?php

	namespace hiweb\components\Fields;


	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;


	class FieldsFactory{

		use hidden_methods;

		private static $fields = [];
		private static $locations = [];


		/**
		 * @param       $fieldId
		 * @return bool|string
		 */
		static private function get_free_global_id( $fieldId ){
			if( !array_key_exists( $fieldId, self::$fields ) ) return $fieldId;
			for( $count = 0; $count < 999; $count ++ ){
				$count = sprintf( '%03u', $count );
				$input_name_id = $fieldId . '_' . $count;
				if( !isset( self::$fields[ $input_name_id ] ) ) return $input_name_id;
			}
			return false;
		}


		/**
		 * @param Field  $Field
		 * @param string $field_options_class - set options class name or leave them
		 * @return mixed|Field_Options - return \hiweb\components\Fields\Field_Options or similar options instance
		 */
		static function add_field( Field $Field, $field_options_class = '\hiweb\components\Fields\Field_Options' ){
			$global_ID = self::get_free_global_id( $Field->ID() );
			$Field->global_ID = $global_ID;
			self::$fields[ $global_ID ] = $Field;
			return $Field->Options();
		}


		/**
		 * @return array|Field[]
		 */
		static function get_fields(){
			return self::$fields;
		}


		/**
		 * Return filed by id or dummy filed
		 * @param $field_ID
		 * @return Field
		 */
		static function get_field( $field_ID ){
			if( array_key_exists( $field_ID, self::$fields ) ){
				return self::$fields[ $field_ID ];
			} else {
				return CacheFactory::get( 'dummy_field_instance', __CLASS__, function(){
					return new Field( '' );
				} )->get_value();
			}
		}


		/**
		 * @param $arr1
		 * @param $arr2
		 * @return array
		 */
		static private function diff( $arr1, $arr2 ){
			$R = [];
			$keys = array_unique( array_merge( array_keys( $arr1 ), array_keys( $arr2 ) ) );
			foreach( $keys as $key ){
				if( !array_key_exists( $key, $arr2 ) ){
					//do nothing
					//$R[ $key ] = $arr1[ $key ];
				} elseif( !array_key_exists( $key, $arr1 ) ) {
					//do nothing
					//$R[ $key ] = $arr1[ $key ];
				} elseif( !is_array( $arr1[ $key ] ) && !is_array( $arr2[ $key ] ) ) {
					if( $arr1[ $key ] != $arr2[ $key ] ){
						$R[ $key ] = $arr1[ $key ];
					}
				} elseif( is_array( $arr1[ $key ] ) || is_array( $arr2[ $key ] ) ) {
					if( !is_array( $arr1[ $key ] ) ) $arr1[ $key ] = [ $arr1[ $key ] ];
					if( !is_array( $arr2[ $key ] ) ) $arr2[ $key ] = [ $arr2[ $key ] ];
					$sub_arr = self::diff( $arr1[ $key ], $arr2[ $key ] );
					if( count( $sub_arr ) > 0 ){
						$R[ $key ] = $sub_arr;
					}
				}
			}
			return $R;
		}


		/**
		 * @param $locationQuery
		 * @return Field[]
		 */
		static function get_field_by_location( $locationQuery ){
			if( is_array( $locationQuery ) ) $locationQuery = json_encode( $locationQuery );
			return CacheFactory::get( $locationQuery, __METHOD__, function(){
				$locationQuery = json_decode( func_get_arg( 0 ), true );
				$R = [];
				foreach( FieldsFactory::get_fields() as $global_id => $Field ){
					$field_location_options = $Field->Options()->Location()->_get_optionsCollect();
					if( count( $field_location_options ) == 0 ) continue;
					$diff = self::diff( $locationQuery, $field_location_options );
					if( count( $diff ) == 0 ){
						$R[ $global_id ] = $Field;
					}
				}
				return $R;
			}, $locationQuery )->get_value();
		}

	}