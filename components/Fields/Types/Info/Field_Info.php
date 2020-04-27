<?php
	
	namespace hiweb\components\Fields\Types\Info;
	
	
	use hiweb\components\Fields\Field;
	
	
	class Field_Info extends Field{
	
		public function get_css(){
			return __DIR__.'/Field_Info.css';
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			return '<div class="hiweb-field-type-info"><input disabled value="'.htmlentities( $value ).'"></div>';
		}
		
		
		public function get_allow_save_field( $value = null ){
			return false;
		}
		
	}