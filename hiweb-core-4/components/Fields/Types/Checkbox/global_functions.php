<?php

	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Checkbox\Field_Checkbox;
	use hiweb\components\Fields\Types\Checkbox\Field_Checkbox_Options;


	if( !function_exists( 'add_field_checkbox' ) ){
		/**
		 * @param $field_ID
		 * @return Field_Checkbox_Options
		 */
		function add_field_checkbox( $field_ID ){
			return FieldsFactory::add_field( new Field_Checkbox( $field_ID ) );
		}
	}