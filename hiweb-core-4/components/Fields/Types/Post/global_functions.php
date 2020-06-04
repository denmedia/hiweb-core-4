<?php
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Post\Field_Post;
	use hiweb\components\Fields\Types\Post\Field_Post_Options;
	
	
	if( !function_exists( 'add_field_post' ) ){
		
		/**
		 * @param $field_ID
		 * @return Field_Post_Options
		 */
		function add_field_post( $field_ID ){
			return FieldsFactory::add_field( new Field_Post( $field_ID ) );
		}
	}