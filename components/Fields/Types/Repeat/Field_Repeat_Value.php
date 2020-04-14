<?php

	namespace hiweb\components\Fields\Types\Repeat;


	class Field_Repeat_Value{

		private $Field_Repeat;
		protected $value_raw = [];
		protected $value_filtered = [];
		protected $rows = [];


		public function __construct( Field_Repeat $Field_Repeat, $value_array ){
			$this->Field_Repeat = $Field_Repeat;
			if( is_array( $value_array ) ){
				$this->value_raw = $value_array;
			}
			foreach( $this->value_raw as $row_values ){
				if( !is_array( $row_values ) ) continue;
				$row_values = array_merge( [ '_flex_row_id' => '', $row_values ] );

			}
			console_info( $this->Field_Repeat->Options()->get_cols() );
		}


		/**
		 * @return bool
		 */
		public function have_cols(){
			return count( $this->Field_Repeat->Options()->get_cols() ) > 0;
		}


		/**
		 * @return bool
		 */
		public function have_rows(){
			return ( count( $this->value_raw ) > 0 );
		}


		/**
		 * @return bool
		 */
		public function have_flex_rows(){
			return ( count( $this->value_raw ) > 0 );
		}


		/**
		 * Return table head html
		 * @param bool   $thead
		 * @param string $handle_title
		 * @return false|string
		 */
		public function get_head_html( $thead = true, $handle_title = '&nbsp;' ){
			ob_start();
			include __DIR__ . '/templates/head.php';
			return ob_get_clean();
		}

	}