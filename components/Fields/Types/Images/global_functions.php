<?php
	
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Images\Field_Images;
	
	
	if( !function_exists( 'add_field_images' ) ){
		/**
		 * @param $field_ID
		 * @return Field_Options|mixed
		 */
		function add_field_images( $field_ID ){
			return FieldsFactory::add_field( new Field_Images( $field_ID ) );
		}
	}
