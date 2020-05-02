<?php

	namespace hiweb\components\Fields\Types\Text;


	use hiweb\components\Fields\Field;


	class Field_Text extends Field{


		protected $options_class = '\hiweb\components\Fields\Types\Text\Field_Text_Options';

		public function get_css(){
			return __DIR__ . '/style.css';
		}
		
		
		/**
		 * @return Field_Text_Options
		 */
		public function Options(){
			return parent::Options();
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}

	}