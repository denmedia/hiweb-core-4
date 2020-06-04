<?php
	
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\File\Field_File;
	
	
	if( !function_exists( 'add_field_file' ) ){
		
		
		/**
		 * @param $field_ID
		 * @return Field_Options
		 */
		function add_field_file( $field_ID ){
			return FieldsFactory::add_field( new Field_File( $field_ID ) );
		}
	}