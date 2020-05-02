<?php
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Image\Field_Image;
	use hiweb\components\Fields\Types\Image\Field_Image_Options;
	
	
	if(!function_exists('add_field_image')){
		
		/**
		 * @param $field_ID
		 * @return Field_Image_Options
		 */
		function add_field_image($field_ID){
			return FieldsFactory::add_field(new Field_Image($field_ID));
		}
		
	}
