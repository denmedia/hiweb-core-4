<?php
	
	namespace hiweb\components\Fields\Types\Separator;
	
	
	use hiweb\components\Fields\Field;
	
	
	class Field_Separator extends Field{
		
		protected $options_class = '\hiweb\components\Fields\Types\Separator\Field_Separator_Options';
		
		
		public function get_css(){
			return __DIR__ . '/Field_Separator.css';
		}
		
		
		public function get_allow_save_field( $value = null ): bool{
			return false;
		}
		
		
		/**
		 * @return Field_Separator_Options
		 */
		public function options(){
			return parent::options();
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
		
	}