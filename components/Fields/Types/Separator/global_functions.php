<?php
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Separator\Field_Separator;
	use hiweb\components\Fields\Types\Separator\Field_Separator_Options;
	
	
	if(!function_exists('add_field_separator')){
		
		function add_field_separator($label = '', $description = ''){
			/** @var Field_Separator_Options $Field_Separator */
			$Field_Separator = FieldsFactory::add_field(new Field_Separator());
			if($label != '') $Field_Separator->separator_label($label);
			if($description != '') $Field_Separator->separator_description($description);
			return $Field_Separator;
		}
		
	}
