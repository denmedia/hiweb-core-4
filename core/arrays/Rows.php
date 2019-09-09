<?php

	namespace hiweb\core\arrays;


	class Rows{

		/** @var Arrays */
		private $array;

		private $rows = null;
		/** @var null|mixed */
		private $current_row = null;
		/** @var null|Rows */
		private $current_sub_rows = null;
		/** @var null|string|int */
		private $current_row_key = null;


		/**
		 * @param $array
		 * @return Rows
		 */
		static function make( $array ){
			return new Rows( $array );
		}


		public function __construct( $array ){
			if( $array instanceof Arrays ){
				$this->array = $array;
			} else {
				$this->array = Arrays::make( $array );
			}
		}


		/**
		 * @return Arrays
		 */
		public function Arrays(){
			return $this->array;
		}


		/**
		 * @return int
		 */
		public function reset_rows(){
			if( $this->array->is_empty() ) return 0;
			$this->rows = $this->array->get();
			$this->current_row = null;
			$this->current_sub_rows = null;
			$this->current_row_key = null;
			return count( $this->rows );
		}


		public function have_rows(){
			if( $this->array->is_empty() ) return false;
			if( !is_array( $this->rows ) ) $this->reset_rows();
			if( count( $this->rows ) == 0 ) return false;
			return true;
		}


		/**
		 * @return mixed|null
		 */
		public function the_row(){
			if( is_array( $this->rows ) && count( $this->rows ) > 0 ){
				reset( $this->rows );
				$this->current_row_key = key( $this->rows );
				$this->current_row = $this->rows[ $this->current_row_key ];
				unset( $this->rows[ $this->current_row_key ] );
				$this->current_sub_rows = new Rows( $this->current_row );
				return $this->current_row;
			}
			return null;
		}


		/**
		 * @return null
		 */
		public function get_current_row(){
			return $this->current_row;
		}


		public function get_current_row_key(){
			return $this->current_row_key;
		}


		/**
		 * @return bool
		 */
		public function is_first_row(){
			if( !is_array( $this->rows ) || $this->array->is_empty() ) return false;
			return ( count( $this->rows ) + 1 ) == $this->array->count();
		}


		/**
		 * @return bool
		 */
		public function is_last_row(){
			if( !is_array( $this->rows ) || $this->array->is_empty() ) return false;
			return count( $this->rows ) == 0;
		}


		/**
		 * @return bool
		 */
		public function is_sub_rows(){
			return is_array( $this->current_row ) && $this->current_sub_rows instanceof Rows;
		}


		/**
		 * Return sub field value
		 * @param null $key
		 * @param null $default
		 * @return array|mixed|null
		 */
		public function get_sub_field( $key = null, $default = null ){
			if( $this->is_sub_rows() ) return $this->current_sub_rows->Arrays()->_( $key, $default ); else return $default;
		}


		/**
		 * @return Rows|null
		 */
		public function get_sub_rows(){
			if( !$this->is_sub_rows() ) return null;
			return $this->current_sub_rows;
		}

	}