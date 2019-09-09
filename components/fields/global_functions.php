<?php


	if( !function_exists( 'get_field' ) ){
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 * @return mixed
		 */
		function get_field( $fieldId, $contextObject = null ){
			return fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_sanitized();
		}
	} else {
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 * @return mixed
		 */
		function _get_field( $fieldId, $contextObject = null ){
			return fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_sanitized();
		}
	}

	if( !function_exists( 'get_field_default' ) ){
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 * @return mixed
		 */
		function get_field_default( $fieldId, $contextObject = null ){
			return fields::get_by_context( $fieldId, $contextObject )->VALUE()->get_sanitized();
		}
	} else {
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 * @return mixed
		 */
		function _get_field_default( $fieldId, $contextObject = null ){
			return fields::get_by_context( $fieldId, $contextObject )->VALUE()->get_sanitized();
		}
	}

	if( !function_exists( 'the_field' ) ){
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 */
		function the_field( $fieldId, $contextObject = null ){
			echo fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_sanitized();
		}
	} else {
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 */
		function _the_field( $fieldId, $contextObject = null ){
			echo fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_sanitized();
		}
	}

	if( !function_exists( 'the_field_default' ) ){
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 */
		function the_field_default( $fieldId, $contextObject = null ){
			echo fields::get_by_context( $fieldId, $contextObject )->VALUE()->get_sanitized();
		}
	} else {
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 */
		function _the_field_default( $fieldId, $contextObject = null ){
			echo fields::get_by_context( $fieldId, $contextObject )->VALUE()->get_sanitized();
		}
	}

	if( !function_exists( 'get_field_content' ) ){
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 * @return mixed
		 */
		function get_field_content( $fieldId, $contextObject = null ){
			return fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_content();
		}
	} else {
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 * @return mixed
		 */
		function _get_field_content( $fieldId, $contextObject = null ){
			return fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_content();
		}
	}

	if( !function_exists( 'the_field_content' ) ){
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 */
		function the_field_content( $fieldId, $contextObject = null ){
			echo fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_content();
		}
	} else {
		/**
		 * @param string                                      $fieldId
		 * @param null|WP_Post|WP_Term|WP_User|string|integer $contextObject
		 */
		function _the_field_content( $fieldId, $contextObject = null ){
			echo fields::get_by_context( $fieldId, $contextObject )->CONTEXT( $contextObject )->VALUE()->get_content();
		}
	}

	use hiweb\components\fields\Fields;
	use hiweb\console;
	use hiweb\fields\rows;


	if( !function_exists( 'have_rows' ) ){
		/**
		 * @param      $fieldId
		 * @param null $contextObject
		 * @return bool
		 */
		function have_rows( $fieldId, $contextObject = null ){
			return rows::have_rows( $fieldId, $contextObject );
		}
	} else {
		console::debug_warn( 'Function [have_rows] is exists...' );
		/**
		 * @param      $fieldId
		 * @param null $contextObject
		 * @return bool
		 */
		function _have_rows( $fieldId, $contextObject = null ){
			return rows::have_rows( $fieldId, $contextObject );
		}
	}

	if( !function_exists( 'the_row' ) ){
		/**
		 * @return array|mixed|null
		 */
		function the_row(){
			return rows::the_row();
		}
	} else {
		console::debug_warn( 'Function [the_row] is exists...' );
		/**
		 * @return array|mixed|null
		 */
		function _the_row(){
			return rows::the_row();
		}
	}

	if( !function_exists( 'reset_rows' ) ){
		/**
		 * @param      $fieldId
		 * @param null $contextObject
		 * @return bool|mixed
		 */
		function reset_rows( $fieldId, $contextObject = null ){
			return rows::reset_rows( $fieldId, $contextObject );
		}
	} else {
		console::debug_warn( 'Function [reset_rows] is exists...' );
		/**
		 * @param      $fieldId
		 * @param null $contextObject
		 * @return bool|mixed
		 */
		function _reset_rows( $fieldId, $contextObject = null ){
			return rows::reset_rows( $fieldId, $contextObject );
		}
	}

	if( !function_exists( 'get_row_layout' ) ){
		/**
		 * @return string
		 */
		function get_row_layout(){
			return rows::get_row_layout();
		}
	} else {
		console::debug_warn( 'Function [get_row_layout] is exists...' );
		/**
		 * @return string
		 */
		function _get_row_layout(){
			return rows::get_row_layout();
		}
	}

	if( !function_exists( 'get_current_row' ) ){
		/**
		 * @return mixed|null
		 */
		function get_current_row(){
			return rows::get_current_row();
		}
	} else {
		console::debug_warn( 'Function [get_current_row] is exists...' );
		/**
		 * @return mixed|null
		 */
		function _get_current_row(){
			return rows::get_current_row();
		}
	}

	if( !function_exists( 'get_sub_field' ) ){
		/**
		 * @param $subField
		 * @return mixed|null
		 */
		function get_sub_field( $subField ){
			return rows::get_sub_field( $subField );
		}
	} else {
		console::debug_warn( 'Function [get_sub_field] is exists...' );
		/**
		 * @param $subField
		 * @return mixed|null
		 */
		function _get_sub_field( $subField ){
			return rows::get_sub_field( $subField );
		}
	}

	if( !function_exists( 'get_sub_field_content' ) ){
		/**
		 * @param $subField
		 * @return mixed|null
		 */
		function get_sub_field_content( $subField ){
			return rows::get_sub_field_content( $subField );
		}
	} else {
		console::debug_warn( 'Function [get_sub_field_content] is exists...' );
		/**
		 * @param $subField
		 * @return mixed|null
		 */
		function _get_sub_field_content( $subField ){
			return rows::get_sub_field_content( $subField );
		}
	}

	if( !function_exists( 'each_rows' ) ){

		function each_rows( $fieldId, $contextObject, $callable ){
			$R = [];
			if( rows::have_rows( $fieldId, $contextObject ) ){
				while( rows::have_rows( $fieldId, $contextObject ) ){
					$row = rows::the_row();
					if( is_callable( $callable ) ) $R[] = call_user_func( $callable, $row ); else $R[] = '<!--no callback [' . $callable . '] -->';
				}
			}

			return $R;
		}
	} else {
		console::debug_warn( 'Function [each_rows] is exists...' );
	}

	if( !function_exists( 'the_row_is_first' ) ){
		/**
		 * @return bool
		 */
		function the_row_is_first(){
			return rows::the_row_is_first();
		}
	} else {
		console::debug_warn( 'Function [the_row_is_first] is exists...' );
	}

	if( !function_exists( 'the_row_is_last' ) ){
		/**
		 * @return bool
		 */
		function the_row_is_last(){
			return rows::the_row_is_last();
		}
	} else {
		console::debug_warn( 'Function [the_row_is_last] is exists...' );
	}

	if( !function_exists( 'get_rows_count' ) ){
		/**
		 * Возвращает количество строк в текущем лупе полей
		 * @return int
		 */
		function get_rows_count(){
			return rows::get_rows_count();
		}
	} else {
		console::debug_warn( 'Function [get_rows_count] is exists...' );
	}
