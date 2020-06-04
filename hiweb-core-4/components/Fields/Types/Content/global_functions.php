<?php

	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Content\Field_Content;
	use hiweb\components\Fields\Types\Content\Field_Content_Options;
	
	
	if( !function_exists( 'add_field_content' ) ){
		/**
		 * @param $field_ID
		 * @return Field_Content_Options
		 */
		function add_field_content( $field_ID ){
			return FieldsFactory::add_field( new Field_Content( $field_ID ) );
		}
	}