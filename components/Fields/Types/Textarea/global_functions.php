<?php
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Textarea\Field_Textarea;
	use hiweb\components\Fields\Types\Textarea\Field_Textarea_Options;
	
	
	if(!function_exists('add_field_textarea')) {
		
		/**
		 * @param $field_ID
		 * @return Field_Textarea_Options
		 */
		function add_field_textarea($field_ID){
			return FieldsFactory::add_field(new Field_Textarea($field_ID));
		}
		
	}