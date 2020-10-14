<?php
	
	use hiweb\core\ArrayObject\ArraysRowsFactory;
	
	
	if( !function_exists( 'get_array' ) ){
		/**
		 * Return instance of ArrayObject from array / mixed item
		 * @aliase get_ArrayObject($array_or_firstElement = [])
		 * @param array|mixed $array_or_firstElement
		 * @return \hiweb\core\ArrayObject\ArrayObject
		 */
		function get_array( $array_or_firstElement = [] ){
			return new \hiweb\core\ArrayObject\ArrayObject( $array_or_firstElement );
		}
	}
	
	if( !function_exists( 'get_ArrayObject' ) ){
		/**
		 * Return instance of arrays\item
		 * @aliase get_array($array_or_firstElement = [])
		 * @param array|mixed $array_or_firstElement
		 * @return \hiweb\core\ArrayObject\ArrayObject
		 */
		function get_ArrayObject( $array_or_firstElement = [] ){
			return new \hiweb\core\ArrayObject\ArrayObject( $array_or_firstElement );
		}
	}
	
	if( !function_exists( 'the_array_get_value' ) ){
		/**
		 * Return latest ArrayObject value by key
		 * @param      $key
		 * @param null $default
		 * @return mixed|null
		 */
		function the_array_get_value( $key, $default = null ){
			return ArraysRowsFactory::get_latest_created()->get_value( $key, $default );
		}
	}
	
	///ROWS
	
	if( !function_exists( 'the_array_reset_rows' ) ){
		/**
		 * Reset rows of ArrayObject to first
		 * @return int
		 */
		function the_array_reset_rows(){
			return ArraysRowsFactory::get_current()->rows()->reset();
		}
	}
	
	if( !function_exists( 'the_array_have_rows' ) ){
		/**
		 * @return bool
		 */
		function the_array_have_rows(){
			return ArraysRowsFactory::get_current()->rows()->have();
		}
	}
	
	if( !function_exists( 'the_array_row_layout' ) ){
		/**
		 * @return bool
		 */
		function the_array_row_layout(){
			return ArraysRowsFactory::get_current()->rows()->get_row_layout();
		}
	}
	
	if( !function_exists( 'the_array_count' ) ){
		/**
		 * @return bool
		 */
		function the_array_count(){
			return ArraysRowsFactory::get_current()->count();
		}
	}
	
	if( !function_exists( 'the_array_row_index' ) ){
		/**
		 * Return current row index
		 * @return bool
		 */
		function the_array_row_index(){
			return ArraysRowsFactory::get_current()->rows()->get_index();
		}
	}
	
	if( !function_exists( 'the_array_row' ) ){
		/**
		 * @return bool
		 */
		function the_array_row(){
			return ArraysRowsFactory::get_current()->rows()->the();
		}
	}
	
	if( !function_exists( 'the_array_each' ) ){
		/**
		 * Each all array items
		 * @param callable $callable - function(mixed $key, mixed $value, Rows $rows)
		 * @return array
		 */
		function the_array_each( $callable ){
			return ArraysRowsFactory::get_current()->rows()->each( $callable );
		}
	}
	
	if( !function_exists( 'the_array_current' ) ){
		/**
		 * @return bool
		 */
		function the_array_current(){
			return ArraysRowsFactory::get_current()->rows()->get_current();
		}
	}
	
	if( !function_exists( 'the_array_current_key' ) ){
		/**
		 * @return bool
		 */
		function the_array_current_key(){
			return ArraysRowsFactory::get_current()->rows()->get_current_key();
		}
	}
	
	if( !function_exists( 'the_array_is_first' ) ){
		/**
		 * @return bool
		 */
		function the_array_is_first(){
			return ArraysRowsFactory::get_current()->rows()->is_first();
		}
	}
	
	if( !function_exists( 'the_array_is_last' ) ){
		/**
		 * @return bool
		 */
		function the_array_is_last(){
			return ArraysRowsFactory::get_current()->rows()->is_last();
		}
	}
	
	if( !function_exists( 'the_array_get_sub_field' ) ){
		/**
		 * Return sub row value
		 * @param string|int $key
		 * @param mixed      $default
		 * @return array|mixed|null
		 */
		function the_array_get_sub_field( $key = null, $default = null ){
			return ArraysRowsFactory::get_current()->rows()->get_sub_field( $key, $default );
		}
	}