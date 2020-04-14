<?php

	namespace hiweb\components\Fields\Types\Text;


	use hiweb\components\Fields\Field;
	use hiweb\components\Includes\IncludesFactory;


	class Field_Text extends Field{


		protected $options_class = '\hiweb\components\Fields\Types\Text\Field_Text_Options';

		public function get_css(){
			return __DIR__ . '/style.css';
		}


		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}

	}