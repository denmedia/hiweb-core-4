<?php
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Select\Field_Select;
	use hiweb\components\Fields\Types\Select\Field_Select_Options;
	
	
	if(!function_exists('add_field_select')) {
		/**
		 * @param $field_ID
		 * @return Field_Select_Options
		 */
		function add_field_select($field_ID){
			return FieldsFactory::add_field(new Field_Select($field_ID));
		}
		
	}