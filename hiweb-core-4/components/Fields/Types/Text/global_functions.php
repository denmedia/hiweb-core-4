<?php

	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Text\Field_Text;
	use hiweb\components\Fields\Types\Text\Field_Text_Options;


	if( !function_exists( 'add_field_text' ) ){
		/**
		 * @param $field_ID
		 * @return Field_Text_Options
		 */
		function add_field_text( $field_ID ){
			return FieldsFactory::add_field( new Field_Text( $field_ID ) );
		}
	}