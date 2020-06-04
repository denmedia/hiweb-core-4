<?php

	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;


	if( !function_exists( 'add_field_repeat' ) ){

		/**
		 * @param $field_ID
		 * @return Field_Repeat_Options
		 */
		function add_field_repeat( $field_ID ){

			return FieldsFactory::add_field( new Field_Repeat( $field_ID ) );
		}
	}
