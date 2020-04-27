<?php
	
	namespace hiweb\components\Fields\Types\Map_Yandex;
	
	
	use hiweb\components\Fields\Field;
	
	
	class Field_MapYandex extends Field{
		
		public function get_css(){
			return [
				__DIR__ . '/Field_MapYandex.css'
			];
		}
		
		
		public function get_js(){
			return [
				'https://api-maps.yandex.ru/2.1-stable/?load=package.standard&lang=ru-RU',
				__DIR__ . '/Field_MapYandex.min.js'
			];
		}
		
		
		/**
		 * @param mixed|null $value
		 * @param bool       $update_meta_process
		 * @return array|int[]|mixed|null
		 */
		public function get_sanitize_admin_value( $value, $update_meta_process = false ){
			if( !is_array( $value ) ){
				$value = [ 55.61397066619661, 37.797730249218105, 8 ];
			}
			else{
				if(!array_key_exists(0, $value)) $value[0] = 55.61397066619661;
				if(!array_key_exists(1, $value)) $value[1] = 37.797730249218105;
				if(!array_key_exists(2, $value)) $value[2] = 8;
			}
			return $value;
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
		
	}