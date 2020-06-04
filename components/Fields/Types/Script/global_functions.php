<?php
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Script\Field_Script;
	use hiweb\components\Fields\Types\Script\Field_Script_Options;
	
	
	if( !function_exists( 'add_field_script' ) ){
		
		/**
		 * @param $field_ID
		 * @return Field_Script_Options
		 */
		function add_field_script( $field_ID ){
			return FieldsFactory::add_field( new Field_Script( $field_ID ) );
		}
	}