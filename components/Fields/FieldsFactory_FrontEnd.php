<?php
	
	namespace hiweb\components\Fields;
	
	
	use hiweb\components\Console\ConsoleFactory;
	use hiweb\components\Structures\StructuresFactory;
	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\ArrayObject\ArrayObject_Rows;
	use hiweb\core\Cache\CacheFactory;
	use WP_Comment;
	use WP_Post;
	use WP_Term;
	use WP_User;
	
	
	class FieldsFactory_FrontEnd{
		
		/** @var ArrayObject_Rows[] */
		static private $rows = [];
		/** @var ArrayObject_Rows[] */
		static private $sub_rows = [];
		/** @var ArrayObject_Rows */
		static private $rows_current_id = '';
		/** @var ArrayObject_Rows */
		static private $sub_rows_current_id = '';
		/** @var ArrayObject_Rows */
		static private $rows_current = [];
		/** @var ArrayObject_Rows */
		static private $sub_rows_current = [];
		
		
		/**
		 * Return fields value
		 * @param      $field_ID
		 * @param null $objectContext
		 * @param null $default
		 * @return mixed|null
		 */
		static function get_value( $field_ID, $objectContext = null, $default = null ){
			$fields_query = FieldsFactory::get_query_from_contextObject( $objectContext );
			$cache_key = StructuresFactory::get_id_from_object( FieldsFactory::sanitize_objectContext( $objectContext ) ) . '-' . $field_ID . '-' . md5( $default );
			return CacheFactory::get( $cache_key, __METHOD__, function(){
				$field_ID = func_get_arg( 0 );
				$objectContext = func_get_arg( 1 );
				$default = func_get_arg( 2 );
				$fields_query = func_get_arg( 3 );
				$fields = FieldsFactory::get_field_by_query( $fields_query );
				if( array_key_exists( $field_ID, $fields ) ){
					$Field = $fields[ $field_ID ];
					$default = is_null( $default ) ? $Field->options()->default_value() : $default;
					$value = $default;
					$contextObject_sanitize = FieldsFactory::sanitize_objectContext( $objectContext );
					if( $contextObject_sanitize instanceof WP_Post ){
						if( metadata_exists( 'post', $contextObject_sanitize->ID, $Field->id() ) ){
							$value = get_post_meta( $contextObject_sanitize->ID, $Field->id(), true );
						}
					}
					elseif( $contextObject_sanitize instanceof WP_Term ){
						//if( metadata_exists( 'term', $contextObject_sanitize->ID, $Field->id() ) ){ //TODO
						$value = get_term_meta( $contextObject_sanitize->term_id, $Field->id(), true );
						//}
					}
					elseif( $contextObject_sanitize instanceof WP_User ){
						//if( metadata_exists( 'user', $contextObject_sanitize->ID, $Field->id() ) ){ //TODO
						$value = get_user_meta( $contextObject_sanitize->ID, $Field->id(), true );
						//}
					}
					elseif( $contextObject_sanitize instanceof WP_Comment ){
						//if( metadata_exists( 'comment', $contextObject_sanitize->ID, $Field->id() ) ){ //TODO
						$value = get_comment_meta( $contextObject_sanitize->ID, $Field->id(), true );
						//}
					}
					elseif( is_string( $contextObject_sanitize ) ){
						$value = get_option( 'hiweb-option-' . $contextObject_sanitize . '-' . $Field->id(), $default );
					}
					return $Field->get_sanitize_admin_value( $value );
				}
				else{
					ConsoleFactory::add( 'Undefined field id and get value', 'warn', __METHOD__, [ $field_ID, $objectContext ], true );
					return $default;
				}
			}, [ $field_ID, $objectContext, $default, $fields_query ] )->get_value();
		}
		
		
		/**
		 * @param                                                    $field_ID
		 * @param null|int|WP_Post|WP_Term|WP_User|WP_Comment|string $objectContext
		 * @return Field
		 */
		static function get_Field( $field_ID, $objectContext = null ){
			$fields_query = FieldsFactory::get_query_from_contextObject( $objectContext );
			$fields = FieldsFactory::get_field_by_query( $fields_query );
			if( array_key_exists( $field_ID, $fields ) ){
				return $fields[ $field_ID ];
			}
			else{
				return FieldsFactory::get_field( '' );
			}
		}
		
		
		/**
		 * @param      $field_ID
		 * @param null $objectContext
		 * @return bool
		 */
		static function is_exists( $field_ID, $objectContext = null ){
			return self::get_Field( $field_ID, $objectContext )->id() != '';
		}
		
		
		/**
		 * @param      $field_ID
		 * @param null $objectContext
		 * @return ArrayObject_Rows
		 * @version 1.1
		 */
		static function get_row( $field_ID, $objectContext = null ){
			$Field = self::get_Field( $field_ID, $objectContext );
			$objectContext = FieldsFactory::sanitize_objectContext( $objectContext );
			$field_context_id = $Field->id() . '-' . ( is_object( $objectContext ) ? StructuresFactory::get_id_from_object($objectContext) : 'options-' . (string)$objectContext );
			if( !array_key_exists( $field_context_id, self::$rows ) ){
				$new_array = new ArrayObject();
				$value = self::get_value( $field_ID, $objectContext );
				if( is_array( $value ) ) $new_array->set( $value );
				self::$rows[ $field_context_id ] = $new_array->rows();
			}
			self::$rows_current_id = $field_context_id;
			self::$rows_current = self::$rows[ $field_context_id ];
			return self::$rows_current;
		}
		
		
		/**
		 * @return ArrayObject_Rows[]
		 */
		static function get_rows(){
			return self::$rows;
		}
		
		
		/**
		 * @return ArrayObject_Rows
		 */
		static function get_current_row(){
			if( !self::$rows_current instanceof ArrayObject_Rows ){
				self::$rows_current = ( new ArrayObject() )->rows();
			}
			return self::$rows_current;
		}
		
		
		/**
		 * @param $col_id
		 * @return array|ArrayObject_Rows|null
		 */
		static function get_sub_field_rows( $col_id ){
			if( self::get_current_row()->get_sub_field_rows( $col_id )->have() ){
				self::$rows_current = self::get_current_row()->get_sub_field_rows( $col_id );
			}
			return self::$rows_current;
		}
		
	}