<?php

	
	
	if(!function_exists('add_field_select')) {
		/**
		 * @param $field_ID
		 * @return \hiweb\components\Fields\Types\Select\Field_Select_Options
		 */
		function add_field_select($field_ID){
			return \hiweb\components\Fields\FieldsFactory::add_field(new \hiweb\components\Fields\Types\Select\Field_Select($field_ID));
		}
		
	}