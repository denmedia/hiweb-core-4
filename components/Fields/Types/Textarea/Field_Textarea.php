<?php
	
	namespace hiweb\components\Fields\Types\Textarea;
	
	
	use hiweb\components\Fields\Field;
	
	
	class Field_Textarea extends Field{
		
		
		protected $options_class = '\hiweb\components\Fields\Types\Textarea\Field_Textarea_Options';
		
		/**
		 * @return Field_Textarea_Options
		 */
		public function options(){
			return parent::options();
		}
		
		
		public function get_css(){
			return [__DIR__.'/textarea.css'];
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
		
	}