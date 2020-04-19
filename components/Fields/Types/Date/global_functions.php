<?php
	
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Date\Field_Date;
	
	
	if( !function_exists( 'add_field_date' ) ){
		
		/**
		 * @param $field_ID
		 * @return Field_Options
		 */
		function add_field_date( $field_ID ){
			return FieldsFactory::add_field( new Field_Date( $field_ID ) );
		}
	}