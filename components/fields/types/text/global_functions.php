<?php

	use hiweb\components\fields\Field;
	use hiweb\components\fields\types\text\Field_Text;
	use hiweb\components\fields\types\text\Field_Text_Options;


	if( !function_exists( 'add_field_text' ) ){

		/**
		 * @param $field_id
		 * @return Field_Text_Options
		 */
		function add_field_text( $field_id ){
			return Field::add( new Field_Text( $field_id ) );
		}
	}