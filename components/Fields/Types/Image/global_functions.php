<?php
	
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Image\Field_Image;
	
	
	if(!function_exists('add_field_image')){
		
		/**
		 * @param $field_ID
		 * @return Field_Options|mixed
		 */
		function add_field_image($field_ID){
			return FieldsFactory::add_field(new Field_Image($field_ID));
		}
		
	}
