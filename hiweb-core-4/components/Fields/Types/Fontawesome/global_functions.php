<?php
	
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Fontawesome\Field_Fontawesome;
	
	
	if( !function_exists( 'add_field_fontawesome' ) ){
		
		/**
		 * @param $field_ID
		 * @return Field_Options|mixed
		 */
		function add_field_fontawesome( $field_ID ){
			return FieldsFactory::add_field( new Field_Fontawesome( $field_ID ) );
		}
	}