<?php

	namespace hiweb\components\Fields\Types\Color;


	use hiweb\components\Fields\Field;


	class Field_Color extends Field{

        /**
         * @return string[]|string
         */
		public function get_css(){
			return [ HIWEB_DIR_VENDOR . '/spectrum-2.0.0/spectrum.css', __DIR__ . '/assets/color.css' ];
		}

        /**
         * @return string[]|string
         */
		public function get_js(){
			return [ HIWEB_DIR_VENDOR . '/spectrum-2.0.0/spectrum.min.js', __DIR__ . '/assets/color.min.js' ];
		}


        /**
         * @param null $value
         * @param null $name
         * @return false|string
         */
		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}

	}