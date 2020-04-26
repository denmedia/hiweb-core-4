<?php
	
	namespace hiweb\components\Fields\Types\Date;
	
	
	use hiweb\components\Fields\Field;
	
	
	class Field_Date extends Field{
		
		protected $options_class = '\hiweb\components\Fields\Types\Date\Field_Date_Options';
		
		public function get_css(){
			return [
				__DIR__ . '/field-date.css',
				HIWEB_DIR_VENDOR . '/jquery.zabuto_calendar/zabuto_calendar.min.css',
				HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.css'
			];
		}
		
		
		public function get_js(){
			return [
				HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.js',
				HIWEB_DIR_VENDOR . '/jquery.zabuto_calendar/zabuto_calendar.min.js',
				__DIR__ . '/field-date.min.js'
			];
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
		
	}