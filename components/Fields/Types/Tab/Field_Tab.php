<?php
	
	namespace hiweb\components\Fields\Types\Tab;
	
	
	class Field_Tab extends \hiweb\components\Fields\Field{
	
		protected $options_class = '\hiweb\components\Fields\Types\Tab\Field_Tab_Options';
		
		
		/**
		 * @return Field_Tab_Options|mixed
		 */
		public function options(){
			return parent::options();
		}
		
		
		public function get_allow_save_field( $value = null ): bool {
			return false;
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			return null;
		}
		
	}