<?php
	
	
	if(!function_exists('add_field_separator')){
		
		function add_field_separator($label = '', $description = ''){
			/** @var \hiweb\components\Fields\Types\Separator\Field_Separator_Options $Field_Separator */
			$Field_Separator = \hiweb\components\Fields\FieldsFactory::add_field(new \hiweb\components\Fields\Types\Separator\Field_Separator());
			if($label != '') $Field_Separator->separator_label($label);
			if($description != '') $Field_Separator->separator_description($description);
			return $Field_Separator;
		}
		
	}
