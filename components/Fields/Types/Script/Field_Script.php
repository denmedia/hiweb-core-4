<?php
	
	namespace hiweb\components\Fields\Types\Script;
	
	
	use hiweb\components\Fields\Field;
	
	
	class Field_Script extends Field{
		
		
		public function get_css(){
			return [ 'wp-codemirror' ];
		}
		
		
		public function get_js(){
			return [ 'wp-codemirror', WPINC.'/js/codemirror/csslint.js', WPINC.'/js/codemirror/htmlhint.js', WPINC.'/js/codemirror/jsonlint.js', WPINC.'/js/codemirror/fakejshint.js', WPINC.'/js/codemirror/htmlhint-kses.js', __DIR__ . '/App.js' ];
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
		
	}