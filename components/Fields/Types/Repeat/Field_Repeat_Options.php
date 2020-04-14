<?php

	namespace hiweb\components\Fields\Types\Repeat;


	use hiweb\components\Fields\Field;
	use hiweb\components\Fields\Field_Options;


	class Field_Repeat_Options extends Field_Options{


		/**
		 * @return array|Field_Repeat_Options|Field_Repeat_Col[][]
		 */
		public function get_cols(){
			$cols = $this->_( 'cols' );
			if( !is_array( $cols ) ){
				$cols = [];
				$this->_( 'cols', [] );
			}
			return $this->_( 'cols' );
		}


		/**
		 * @return array
		 */
		public function get_cols_simple(){
			$R = [];
			foreach($this->get_cols() as $group => $cols) {
				if(!is_array($cols)) continue;
				foreach($cols as $global_id => $col) {
					$R[$group][$global_id] = $col->_get_optionsCollect();
				}
			}
			return $R;
		}


		/**
		 * @return bool
		 */
		public function have_cols(){
			return count( $this->get_cols() ) > 0;
		}


		/**
		 * @param                     $group_name
		 * @param Field|Field_Options $Field_or_FieldOptions
		 * @return Field_Repeat_Col
		 */
		public function add_col_flex_field( $group_name, $Field_or_FieldOptions ){
			$cols = $this->get_cols();
			$col = new Field_Repeat_Col( $this->Field(), $Field_or_FieldOptions );
			$cols[ $group_name ][ $col->Field()->ID() ] = $col;
			return $col;
		}


		/**
		 * @param Field|Field_Options $Field_or_FieldOptions
		 * @return Field_Repeat_Col
		 */
		public function add_col_field( $Field_or_FieldOptions ){
			return $this->add_col_flex_field( '', $Field_or_FieldOptions );
		}

	}