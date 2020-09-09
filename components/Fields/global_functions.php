<?php
	
	use hiweb\components\Fields\FieldsFactory_FrontEnd;
	
	
	if( !function_exists( 'get_field' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null|mixed                                         $default
		 * @return mixed|null
		 */
		function get_field( $field_ID, $contextObject = null, $default = null ){
			return FieldsFactory_FrontEnd::get_value( $field_ID, $contextObject, $default );
		}
	}
	
	if( !function_exists( '_get_field' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 * @return mixed|null
		 */
		function _get_field( $field_ID, $contextObject = null, $default = null ){
			return FieldsFactory_FrontEnd::get_value( $field_ID, $contextObject, $default );
		}
	}
	
	if( !function_exists( 'get_field_content' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 * @return mixed|null
		 */
		function get_field_content( $field_ID, $contextObject = null, $default = null ){
			return FieldsFactory_FrontEnd::get_value( $field_ID, $contextObject, $default );
		}
	}
	
	if( !function_exists( '_get_field_content' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 * @return mixed|null
		 */
		function _get_field_content( $field_ID, $contextObject = null, $default = null ){
			return FieldsFactory_FrontEnd::get_value( $field_ID, $contextObject, $default );
		}
	}
	
	if( !function_exists( 'the_field_content' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 */
		function the_field_content( $field_ID, $contextObject = null, $default = null ){
			echo FieldsFactory_FrontEnd::get_value( $field_ID, $contextObject, $default );
		}
	}
	
	if( !function_exists( '_the_field_content' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 */
		function _the_field_content( $field_ID, $contextObject = null, $default = null ){
			echo FieldsFactory_FrontEnd::get_value( $field_ID, $contextObject, $default );
		}
	}
	
	if( !function_exists( 'the_field' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 */
		function the_field( $field_ID, $contextObject = null, $default = null ){
			echo FieldsFactory_FrontEnd::get_value( $field_ID, $contextObject, $default );
		}
	}
	
	if( !function_exists( '_the_field' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 */
		function _the_field( $field_ID, $contextObject = null, $default = null ){
			echo FieldsFactory_FrontEnd::get_value( $field_ID, $contextObject, $default );
		}
	}
	
	if( !function_exists( 'get_field_default' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 * @return mixed|null
		 */
		function get_field_default( $field_ID, $contextObject = null, $default = null ){
			return FieldsFactory_FrontEnd::get_Field( $field_ID, $contextObject )->options()->default_value();
		}
	}
	
	if( !function_exists( '_get_field_default' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 * @return mixed|null
		 */
		function _get_field_default( $field_ID, $contextObject = null, $default = null ){
			return FieldsFactory_FrontEnd::get_Field( $field_ID, $contextObject )->options()->default_value();
		}
	}
	
	if( !function_exists( 'the_field_default' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 */
		function the_field_default( $field_ID, $contextObject = null, $default = null ){
			echo FieldsFactory_FrontEnd::get_Field( $field_ID, $contextObject )->options()->default_value();
		}
	}
	
	if( !function_exists( '_the_field_default' ) ){
		
		/**
		 * @param                                                    $field_ID
		 * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
		 * @param null                                               $default
		 */
		function _the_field_default( $field_ID, $contextObject = null, $default = null ){
			echo FieldsFactory_FrontEnd::get_Field( $field_ID, $contextObject )->options()->default_value();
		}
	}
	
	////ROWS
	
	if( !function_exists( 'have_rows' ) ){
		/**
		 * @param      $field_Id
		 * @param null $objectContext
		 * @return bool
		 */
		function have_rows( $field_Id, $objectContext = null ){
			return FieldsFactory_FrontEnd::get_row( $field_Id, $objectContext )->have();
		}
	}
	if( !function_exists( '_have_rows' ) ){
		/**
		 * @param      $field_Id
		 * @param null $objectContext
		 * @return bool
		 */
		function _have_rows( $field_Id, $objectContext = null ){
			return FieldsFactory_FrontEnd::get_row( $field_Id, $objectContext )->have();
		}
	}
	
	if( !function_exists( 'the_row' ) ){
		/**
		 * @return array|mixed|null
		 */
		function the_row(){
			return FieldsFactory_FrontEnd::get_current_row()->the();
		}
	}
	
	if( !function_exists( '_the_row' ) ){
		/**
		 * @return array|mixed|null
		 */
		function _the_row(){
			return FieldsFactory_FrontEnd::get_current_row()->the();
		}
	}
	
	if( !function_exists( 'reset_rows' ) ){
		/**
		 * @param      $fieldId
		 * @param null $contextObject
		 * @return bool|mixed
		 */
		function reset_rows( $fieldId, $contextObject = null ){
			return FieldsFactory_FrontEnd::get_current_row()->reset();
		}
	}
	
	if( !function_exists( '_reset_rows' ) ){
		/**
		 * @param      $fieldId
		 * @param null $contextObject
		 * @return bool|mixed
		 */
		function _reset_rows( $fieldId, $contextObject = null ){
			return FieldsFactory_FrontEnd::get_current_row()->reset();
		}
	}
	
	if( !function_exists( 'get_row_layout' ) ){
		/**
		 * @return string
		 */
		function get_row_layout(){
			return FieldsFactory_FrontEnd::get_current_row()->get_sub_field( '_flex_row_id' );
		}
	}
	
	if( !function_exists( '_get_row_layout' ) ){
		/**
		 * @return string
		 */
		function _get_row_layout(){
			return FieldsFactory_FrontEnd::get_current_row()->get_sub_field( '_flex_row_id', '' );
		}
	}
	
	if( !function_exists( 'get_current_row' ) ){
		/**
		 * @return mixed|null
		 */
		function get_current_row(){
			return FieldsFactory_FrontEnd::get_current_row()->get_current()->get();
		}
	}
	if( !function_exists( '_get_current_row' ) ){
		/**
		 * @return mixed|null
		 */
		function _get_current_row(){
			return FieldsFactory_FrontEnd::get_current_row()->get_current()->get();
		}
	}
	
	if( !function_exists( 'get_sub_field' ) ){
		/**
		 * @param $subField
		 * @return mixed|null
		 */
		function get_sub_field( $subField, $default = null ){
			if( FieldsFactory_FrontEnd::get_current_row()->have_sub_field( $subField ) ){
				return FieldsFactory_FrontEnd::get_current_row()->get_sub_field( $subField, $default );
			}
			else{
				//$rows = array_reverse( FieldsFactory_FrontEnd::get_rows() );
				$rows = FieldsFactory_FrontEnd::get_rows();
				foreach( $rows as $row_id => $row ){
					if( $row->have_sub_field( $subField ) ){
						return $row->get_sub_field( $subField, $default );
					}
				}
			}
			return $default;
		}
	}
	if( !function_exists( '_get_sub_field' ) ){
		/**
		 * @param $subField
		 * @return mixed|null
		 */
		function _get_sub_field( $subField ){
			return FieldsFactory_FrontEnd::get_current_row()->get_sub_field( $subField );
		}
	}
	
	if( !function_exists( 'have_sub_rows' ) ){
		/**
		 * @param string $sub_id
		 * @return bool
		 */
		function have_sub_rows( $sub_id ){
			return FieldsFactory_FrontEnd::get_sub_field_rows( $sub_id )->have();
		}
	}
	
	if( !function_exists( '_have_sub_rows' ) ){
		/**
		 * @param string $sub_id
		 * @return bool
		 */
		function _have_sub_rows( $sub_id ){
			return FieldsFactory_FrontEnd::get_sub_field_rows( $sub_id )->have();
		}
	}
	
	///TODO:...
	
	//	if( !function_exists( 'get_sub_field_content' ) ){
	//		/**
	//		 * @param $subField
	//		 * @return mixed|null
	//		 */
	//		function get_sub_field_content( $subField ){
	//			return rows::get_sub_field_content( $subField );
	//		}
	//	}
	//
	//	if( !function_exists( 'each_rows' ) ){
	//
	//		function each_rows( $fieldId, $contextObject, $callable ){
	//			$R = [];
	//			if( rows::have_rows( $fieldId, $contextObject ) ){
	//				while( rows::have_rows( $fieldId, $contextObject ) ){
	//					$row = rows::the_row();
	//					if( is_callable( $callable ) ) $R[] = call_user_func( $callable, $row );
	//					else $R[] = '<!--no callback [' . $callable . '] -->';
	//				}
	//			}
	//
	//			return $R;
	//		}
	//	}
	//
	//	if( !function_exists( 'the_row_is_first' ) ){
	//		/**
	//		 * @return bool
	//		 */
	//		function the_row_is_first(){
	//			return rows::the_row_is_first();
	//		}
	//	}
	//
	//	if( !function_exists( 'the_row_is_last' ) ){
	//		/**
	//		 * @return bool
	//		 */
	//		function the_row_is_last(){
	//			return rows::the_row_is_last();
	//		}
	//	}
	//
	//	if( !function_exists( 'get_rows_count' ) ){
	//		/**
	//		 * Возвращает количество строк в текущем лупе полей
	//		 * @return int
	//		 */
	//		function get_rows_count(){
	//			return rows::get_rows_count();
	//		}
	//	}