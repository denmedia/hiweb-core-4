<?php
	
	namespace hiweb\components\Fields;
	
	
	use hiweb\components\Console\Console;
	use hiweb\components\Console\ConsoleFactory;
	use hiweb\components\Fields\Field_Options\Field_Options_Location;
	use hiweb\components\Structures\StructuresFactory;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;
	use theme\breadcrumbs;
	use WP_Post;
	
	
	class FieldsFactory{
		
		use hidden_methods;
		
		
		private static $fields = [];
		static $fieldIds_by_locations = [];
		
		/** @var Field_Options_Location */
		static $last_location_instance;
		
		
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
			$global_ID = self::get_free_global_id( $Field->id() );
			$Field->global_ID = $global_ID;
			self::$fields[ $global_ID ] = $Field;
			CacheFactory::remove_group( '\hiweb\components\Fields\FieldsFactory::get_field_by_query' );
			return $Field->options();
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
		 * @param $require_arr
		 * @param $candidate_arr
		 * @return array
		 * @deprecated
		 */
		static private function diff( $require_arr, $candidate_arr ){
			$R = [];
			$keys = array_unique( array_merge( array_keys( $require_arr ), array_keys( $candidate_arr ) ) );
			foreach( $keys as $key ){
				if( count( func_get_args() ) == 2 && !array_key_exists( $key, $candidate_arr ) ){
					$R[ $key ] = $require_arr[ $key ];
				}
				elseif( !array_key_exists( $key, $require_arr ) ){
					//do nothing
					//$R[ $key ] = $arr1[ $key ];
				}
				elseif( array_key_exists( $key, $require_arr ) && array_key_exists( $key, $candidate_arr ) && !is_array( $require_arr[ $key ] ) && !is_array( $candidate_arr[ $key ] ) ){
					if( $require_arr[ $key ] != $candidate_arr[ $key ] ){
						$R[ $key ] = $require_arr[ $key ];
					}
				}
				else if( array_key_exists( $key, $require_arr ) && is_array( $require_arr[ $key ] ) && count( $require_arr[ $key ] ) == 0 && !array_key_exists( $key, $candidate_arr ) ){
					$R[ $key ] = [];
				}
				elseif( array_key_exists( $key, $require_arr ) && array_key_exists( $key, $candidate_arr ) && ( is_array( $require_arr[ $key ] ) || is_array( $candidate_arr[ $key ] ) ) ){
					if( !is_array( $require_arr[ $key ] ) ) $require_arr[ $key ] = [ $require_arr[ $key ] ];
					if( !is_array( $candidate_arr[ $key ] ) ) $candidate_arr[ $key ] = [ $candidate_arr[ $key ] ];
					$sub_arr = self::diff( $require_arr[ $key ], $candidate_arr[ $key ], false );
					if( count( $sub_arr ) > 0 ){
						$R[ $key ] = $sub_arr;
					}
				}
			}
			return $R;
		}
		
		
		/**
		 * @param $pattern
		 * @param $array
		 * @return int
		 */
		static private function preg_array_key_exists( $pattern, $array ){
			$keys = array_keys( $array );
			return (int)preg_grep( $pattern, $keys );
		}
		
		
		/**
		 * @param array       $fieldLocation
		 * @param array       $locationQuery
		 * @param null|string $parent_key
		 * @param string|null $parent_operator
		 * @return array
		 */
		static function diff_2( $locationQuery, $fieldLocation, $parent_key = null, $parent_operator = '&' ){
			$R = [];
			///Prepare Arrays
			$is_end_of_branch = true;
			$operator_by_key = [];
			$locationQuery_emptyKeys = [];
			$locationQuery_filtered = [];
			$fieldLocation_filtered = [];
			foreach( [ 'locationQuery' => $locationQuery, 'fieldLocation' => $fieldLocation ] as $name => $arr ){
				$tmp_result_arr = [];
				foreach( $arr as $key => $value ){
					if( !is_numeric( $key ) ){
						$is_end_of_branch = false;
						///
						$tmp_operator = substr( $key, 0, 1 );
						if( in_array( $tmp_operator, [ '&', '|', '!', '~', '?' ] ) ){
							$tmp_key = substr( $key, 1 );
						}
						else{
							$tmp_key = $key;
							$tmp_operator = '&';
						}
						$operator_by_key[ $tmp_key ] = $tmp_operator;
						$tmp_result_arr[ $tmp_key ] = (array)$value;
						if( $name == 'locationQuery' && count( (array)$value ) == 0 ){
							$locationQuery_emptyKeys[] = $tmp_key;
						}
					}
					else{
						$tmp_result_arr[] = $value;
					}
				}
				switch( $name ){
					case 'locationQuery' :
						$locationQuery_filtered = $tmp_result_arr;
						break;
					case 'fieldLocation' :
						$fieldLocation_filtered = $tmp_result_arr;
						break;
				}
			}
			///Compare arrays
			$matches = 0;
			$mismatches = 0;
			foreach( $fieldLocation_filtered as $key => $value ){
				if( $is_end_of_branch ){
					if( !in_array( $value, $locationQuery_filtered ) ){
						$R[] = $value;
						$mismatches ++;
					}
					else{
						$matches ++;
					}
				}
				else{
					if( array_key_exists( $key, $locationQuery_filtered ) && count( $locationQuery_filtered[ $key ] ) > 0 && count( $value ) > 0 ){
						$tmp_result_arr = self::diff_2( $locationQuery_filtered[ $key ], $fieldLocation_filtered[ $key ], $key, $operator_by_key[ $key ] );
						if( count( $tmp_result_arr ) > 0 ){
							$R[ $key ] = $tmp_result_arr;
							$mismatches ++;
						}
						else{
							$matches ++;
						}
					}
					else{
						if( is_null( $parent_key ) ){
							$R[ $key ] = $value;
							$mismatches ++;
						}
						else{
							$matches ++;
						}
					}
				}
			}
			///Empty Location Query
			foreach( $locationQuery_emptyKeys as $key ){
				if( !array_key_exists( $key, $fieldLocation_filtered ) || count( $fieldLocation_filtered[ $key ] ) == 0 ){
					$R[ $key ] = [];
					$mismatches ++;
				}
			}
			///Operator Compare
			switch( $parent_operator ){
				case '|':
					if( $matches > 0 ) $R = [];
					break;
				case '!':
					if( $mismatches > 0 ) $R = [];
					break;
				case '~':
					if( $matches == 0 && $mismatches > 0 ) $R = [];
					break;
				case '?':
					if( array_key_exists( $parent_key, $locationQuery_filtered ) ) $R = [];
					break;
				default:
					//do nothing
					if( $mismatches == 0 ) $R = [];
					break;
			}
			return $R;
		}
		
		
		/**
		 * @param $locationQuery
		 * @return Field[]
		 */
		static function get_field_by_query( $locationQuery ){
			return CacheFactory::get( json_encode( $locationQuery ), '\hiweb\components\Fields\FieldsFactory::get_field_by_query', function(){
				$locationQuery = func_get_arg( 0 );
				if( is_string( $locationQuery ) ) $locationQuery = json_decode( $locationQuery, true );
				$Fields = [];
				foreach( FieldsFactory::get_fields() as $global_id => $Field ){
					$field_location_options = $Field->options()->location()->_get_optionsCollect();
					if( count( $field_location_options ) == 0 ) continue;
					$diff = self::diff_2( $locationQuery, $field_location_options );
					if( count( $diff ) == 0 ){
						$Fields[ $Field->id() ] = $Field;
					}
				}
				return $Fields;
			}, [ $locationQuery ] )->get_value();
		}
		
		
		/**
		 * @param $objectContext
		 * @return array|object|WP_Post|null
		 */
		static function sanitize_objectContext( $objectContext = null ){
			///prepare object
			if( is_null( $objectContext ) ){
				if( function_exists( 'get_queried_object' ) ){
					if( get_queried_object() instanceof \WP_Post_Type && get_queried_object()->name == 'product' && function_exists( 'WC' ) ){
						return get_post( get_option( 'woocommerce_shop_page_id' ) );
					}
					else{
						return get_queried_object();
					}
				}
			}
			elseif( is_numeric( $objectContext ) ){
				return get_post( $objectContext );
			}
			return $objectContext;
		}
		
		
		static function get_query_from_contextObject( $objectContext = null ){
			$R = [];
			$objectContext = self::sanitize_objectContext( $objectContext );
			///
			if( $objectContext instanceof WP_Post ){
				if( $objectContext->post_type == 'nav_menu_item' ){
					$R = [
						'nav_menu' => [
							'ID' => $objectContext->ID
						]
					];
				}
				else{
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
			}
			elseif( $objectContext instanceof \WP_Term ){
				$R = [
					'taxonomy' => [
						'term_id' => $objectContext->term_id,
						'term_taxonomy_id' => $objectContext->term_taxonomy_id,
						'name' => $objectContext->name,
						'taxonomy' => $objectContext->taxonomy,
						'slug' => $objectContext->slug,
						'count' => $objectContext->count,
						'parent' => $objectContext->parent,
						'term_group' => $objectContext->term_group
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