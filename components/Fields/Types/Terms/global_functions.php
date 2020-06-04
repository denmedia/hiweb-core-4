<?php
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Terms\Field_Terms;
	use hiweb\components\Fields\Types\Terms\Field_Terms_Options;
	
	
	if(!function_exists('add_field_terms')){
		
		/**
		 * @param $field_ID
		 * @return Field_Terms_Options
		 */
		function add_field_terms($field_ID){
			return FieldsFactory::add_field(new Field_Terms($field_ID));
		}
		
	}