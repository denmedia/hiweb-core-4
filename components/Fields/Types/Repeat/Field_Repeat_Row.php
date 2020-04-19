<?php
	
	namespace hiweb\components\Fields\Types\Repeat;
	
	
	class Field_Repeat_Row{
		
		private $Field;
		private $row_index = 0;
		private $row_cols;
		private $row_raw;
		private $flex_row_id = '';
		private $row_name_prefix;
		
		
		public function __construct( Field_Repeat $Field, $row_index = 0, $cols = [], $row_raw = [] ){
			$this->Field = $Field;
			$this->row_index = $row_index;
			$this->row_cols = $cols;
			$this->row_raw = $row_raw;
			$this->row_name_prefix = $Field->get_sanitize_admin_name() . "[{$this->$row_index}]";
			if( array_key_exists( '_flex_row_id', $row_raw ) ) $this->flex_row_id = $row_raw['_flex_row_id'];
		}
		
		
		public function the(){
			include __DIR__ . '/templates/row.php';
		}
		
		
		/**
		 * @return Field_Repeat
		 */
		public function Field(){
			return $this->Field;
		}
		
		
		/**
		 * @return mixed|string
		 */
		public function get_flex_row_id(){
			return $this->flex_row_id;
		}
		
		
		/**
		 * @return Field_Repeat_Col[]
		 */
		public function get_cols(){
			return $this->row_cols;
		}
		
		
		/**
		 * @return int
		 */
		public function get_index(){
			return $this->row_index;
		}
		
		
		/**
		 * @param $col_id
		 * @return string
		 */
		public function get_col_input_name( $col_id = '' ){
			return $this->row_name_prefix . ( $col_id == '' ? '' : "[{$col_id}]" );
		}
		
		
		/**
		 * @param string     $col_id
		 * @param null|mixed $default
		 * @return mixed|null
		 */
		public function get_col_input_value( $col_id, $default = null ){
			$R = $default;
			if( array_key_exists( $col_id, $this->row_raw ) ) $R = $this->row_raw[ $col_id ];
			if( array_key_exists( $col_id, $this->get_cols() ) && !is_null( $this->get_cols()[ $col_id ]->Field()->Options()->default_value() ) ){
				$R = $this->get_cols()[ $col_id ]->Field()->Options()->default_value();
			}
			return $R;
		}
		
	}