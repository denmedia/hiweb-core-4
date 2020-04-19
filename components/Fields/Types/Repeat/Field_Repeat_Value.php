<?php
	
	namespace hiweb\components\Fields\Types\Repeat;
	
	
	class Field_Repeat_Value{
		
		private $Field_Repeat;
		protected $value_raw = [];
		protected $value_filtered = [];
		/** @var Field_Repeat_Row[] */
		protected $rows;
		
		
		public function __construct( Field_Repeat $Field_Repeat, $value_array = [] ){
			$this->Field_Repeat = $Field_Repeat;
			if( is_array( $value_array ) ){
				$this->value_raw = $value_array;
			}
			foreach( $this->value_raw as $row_values ){
				if( !is_array( $row_values ) ) continue;
				$row_values = array_merge( [ '_flex_row_id' => '' ], $row_values );
				$this->value_filtered[] = $row_values;
			}
		}
		
		
		/**
		 * @return array
		 */
		public function get(){
			return $this->value_filtered;
		}
		
		
		//		/**
		//		 * @return bool
		//		 */
		//		public function have_cols(){
		//			return count( $this->Field_Repeat->Options()->get_cols() ) > 0;
		//		}
		
		/**
		 * @return bool
		 */
		public function have_rows(){
			return ( count( $this->value_raw ) > 0 );
		}
		
		
//		/**
//		 * @return bool
//		 */
//		public function have_flex_rows(){
//			return !( in_array( '', $this->Field_Repeat->Options()->get_flex_ids() ) && count( $this->Field_Repeat->Options()->get_flex_ids() ) );
//		}
		
		
		/**
		 * @return array|Field_Repeat_Row[]
		 */
		public function get_rows(){
			if( !is_array( $this->rows ) ){
				$this->rows = [];
				$cols_by_flex = $this->Field_Repeat->Options()->get_cols();
				foreach( $this->value_filtered as $row_index => $row_raw ){
					$flex_row_id = array_key_exists( '_flex_row_id', $row_raw ) ? $row_raw['_flex_row_id'] : '';
					if( array_key_exists( $flex_row_id, $cols_by_flex ) ){
						$this->rows[ $row_index ] = new Field_Repeat_Row( $this->Field_Repeat, $row_index, $cols_by_flex[ $flex_row_id ], $row_raw );
					}
				}
			}
			return $this->rows;
		}
		
	}