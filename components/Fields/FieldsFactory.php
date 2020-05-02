<?php
	
	namespace hiweb\components\Fields;
	
	
	use hiweb\components\Structures\Structure;
	use hiweb\components\Structures\StructuresFactory;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;
	use WP_Post;
	
	
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
			CacheFactory::remove_group('\hiweb\components\Fields\FieldsFactory::get_field_by_query');
			return $Field->Options();
		}
		
		
		/**
		 * @return array|Field[]
		 */
		static function get_fields(){
			return self::$fields;
		}
		
		
		/**
		 * Return Field[] by id, use * symbol
		 * @param string $field_ID
		 * @return array|Field[]
		 */
		static function get_search_fields_by_id( $field_ID = 'field*' ){
			$R = [];
			$field_ID = strtr( $field_ID, [ '*' => '.*', '-' => '\-' ] );
			foreach( self::get_fields() as $id => $field ){
				if( preg_match( '/^' . $field_ID . '$/i', $id ) > 0 ) $R[ $field->global_ID() ] = $field;
			}
			return $R;
		}
		
		
		/**
		 * Return filed by id or dummy filed
		 * @param $field_global_ID
		 * @return Field
		 */
		static function get_field( $field_global_ID ){
			if( array_key_exists( $field_global_ID, self::$fields ) ){
				return self::$fields[ $field_global_ID ];
			}
			else{
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
				if( count( func_get_args() ) == 2 && !array_key_exists( $key, $arr2 ) ){
					$R[ $key ] = $arr1[ $key ];
				}
				elseif( !array_key_exists( $key, $arr1 ) ){
					//do nothing
					//$R[ $key ] = $arr1[ $key ];
				}
				elseif( array_key_exists( $key, $arr1 ) && array_key_exists( $key, $arr2 ) && !is_array( $arr1[ $key ] ) && !is_array( $arr2[ $key ] ) ){
					if( $arr1[ $key ] != $arr2[ $key ] ){
						$R[ $key ] = $arr1[ $key ];
					}
				}
				else if( array_key_exists( $key, $arr1 ) && is_array( $arr1[ $key ] ) && count( $arr1[ $key ] ) == 0 && !array_key_exists( $key, $arr2 ) ){
					$R[ $key ] = [];
				}
				elseif( array_key_exists( $key, $arr1 ) && array_key_exists( $key, $arr2 ) && ( is_array( $arr1[ $key ] ) || is_array( $arr2[ $key ] ) ) ){
					if( !is_array( $arr1[ $key ] ) ) $arr1[ $key ] = [ $arr1[ $key ] ];
					if( !is_array( $arr2[ $key ] ) ) $arr2[ $key ] = [ $arr2[ $key ] ];
					$sub_arr = self::diff( $arr1[ $key ], $arr2[ $key ], false );
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
		static function get_field_by_query( $locationQuery ){
			return CacheFactory::get(json_encode($locationQuery), '\hiweb\components\Fields\FieldsFactory::get_field_by_query', function(){
				$locationQuery = func_get_arg(0);
				if( is_string( $locationQuery ) ) $locationQuery = json_decode( $locationQuery, true );
				$Fields = [];
				foreach( FieldsFactory::get_fields() as $global_id => $Field ){
					$field_location_options = $Field->Options()->Location()->_get_optionsCollect();
					if( count( $field_location_options ) == 0 ) continue;
					$diff = self::diff( $locationQuery, $field_location_options );
					if( count( $diff ) == 0 ){
						$Fields[ $Field->global_ID() ] = $Field;
					}
				}
				return $Fields;
			}, [$locationQuery])->get_value();
		}
		
		
		
		
		/**
		 * @param $objectContext
		 * @return array|object|WP_Post|null
		 */
		static function sanitize_objectContext( $objectContext = null ){
			///prepare object
			if( is_null( $objectContext ) ){
				if( function_exists( 'get_queried_object' ) ){
					$objectContext = get_queried_object();
				}
			}
			elseif( is_numeric( $objectContext ) ){
				$objectContext = get_post( $objectContext );
			}
			return $objectContext;
		}
		
		
		static function get_query_from_contextObject( $objectContext = null ){
			$R = [];
			$objectContext = self::sanitize_objectContext( $objectContext );
			///
			if( $objectContext instanceof WP_Post ){
				$R = [
					'post_type' => [
						'ID' => $objectContext->ID,
						'post_type' => $objectContext->post_type,
						'post_name' => $objectContext->post_name,
						'post_status' => $objectContext->post_status,
						'comment_status' => $objectContext->comment_status,
						'post_parent' => $objectContext->post_parent,
						'has_taxonomy' => $objectContext->has_taxonomy,
						'front_page' => StructuresFactory::get_front_page_id() == $objectContext->ID
					]
				];
			}
			elseif( is_string( $objectContext ) ){
				$R = [
					'options' => $objectContext
				];
			}
			return $R;
		}
		
	}