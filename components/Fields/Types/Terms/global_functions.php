<?php

	
	if(!function_exists('add_field_terms')){
		
		/**
		 * @param $field_ID
		 * @return \hiweb\components\Fields\Types\Terms\Field_Terms_Options
		 */
		function add_field_terms($field_ID){
			return \hiweb\components\Fields\FieldsFactory::add_field(new \hiweb\components\Fields\Types\Terms\Field_Terms($field_ID));
		}
		
	}