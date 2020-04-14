<?php

	namespace hiweb\core\ArrayObject;


	use hiweb\core\ArrayObject\ArrayObject;


	class ArrayObject_Rows{

		/** @var ArrayObject */
		private $array;

		private $rows = null;
		/** @var null|mixed */
		private $current_row = null;
		/** @var null|ArrayObject_Rows */
		private $current_sub_rows = null;
		/** @var null|string|int */
		private $current_row_key = null;


		public function __construct( $array ){
			if( $array instanceof ArrayObject ){
				$this->array = $array;
			} else {
				$this->array = new ArrayObject( $array );
			}
		}


		/**
		 * @return ArrayObject
		 */
		public function ArrayObject(){
			return $this->array;
		}


		/**
		 * Reset rows of ArrayObject to first
		 * @return int
		 */
		public function reset(){
			if( $this->array->is_empty() ) return 0;
			$this->rows = $this->array->get();
			$this->current_row = null;
			$this->current_sub_rows = null;
			$this->current_row_key = null;
			return count( $this->rows );
		}


		public function have(){
			if( $this->array->is_empty() ) return false;
			if( !is_array( $this->rows ) ) $this->reset();
			if( count( $this->rows ) == 0 ) return false;
			return true;
		}


		/**
		 * @return mixed|null
		 */
		public function the(){
			if( is_array( $this->rows ) && count( $this->rows ) > 0 ){
				reset( $this->rows );
				$this->current_row_key = key( $this->rows );
				$this->current_row = $this->rows[ $this->current_row_key ];
				unset( $this->rows[ $this->current_row_key ] );
				$this->current_sub_rows = new ArrayObject_Rows( $this->current_row );
				return $this->current_row;
			}
			return null;
		}


		/**
		 * @param $callable - user function, call event array item
		 * @return array - return array of result call user function
		 */
		public function each( $callable ){
			$R = [];
			if( is_callable( $callable ) ){
				$this->reset();
				if( $this->have() ){
					while( $this->have() ){
						$this->the();
						$R[ $this->get_current_key() ] = call_user_func_array( $callable, [ $this->get_current_key(), is_array( $this->get_current() ) ? new ArrayObject( $this->get_current() ) : $this->get_current(), $this ] );
					}
				}
			}
			return $R;
		}


		/**
		 * @return null
		 */
		public function get_current(){
			return is_array( $this->current_row ) ? new ArrayObject( $this->current_row ) : $this->current_row;
		}


		public function get_current_key(){
			return $this->current_row_key;
		}


		/**
		 * @return bool
		 */
		public function is_first(){
			if( !is_array( $this->rows ) || $this->array->is_empty() ) return false;
			return ( count( $this->rows ) + 1 ) == $this->array->count();
		}


		/**
		 * @return bool
		 */
		public function is_last(){
			if( !is_array( $this->rows ) || $this->array->is_empty() ) return false;
			return count( $this->rows ) == 0;
		}


		/**
		 * @return bool
		 */
		public function is_sub_rows(){
			return is_array( $this->current_row ) && $this->current_sub_rows instanceof ArrayObject_Rows;
		}


		/**
		 * Return sub field value
		 * @param null $key
		 * @param null $default
		 * @return array|mixed|null
		 */
		public function get_sub_field( $key = null, $default = null ){
			if( $this->is_sub_rows() ) return $this->current_sub_rows->ArrayObject()->_( $key, $default ); else return $default;
		}


		/**
		 * @return ArrayObject_Rows|null
		 */
		public function get_sub_rows(){
			if( !$this->is_sub_rows() ) return null;
			return $this->current_sub_rows;
		}

	}