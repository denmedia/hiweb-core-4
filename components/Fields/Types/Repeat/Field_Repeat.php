<?php
	
	namespace hiweb\components\Fields\Types\Repeat;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\components\Fields\FieldsFactory_Admin;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\Strings;
	
	
	class Field_Repeat extends Field{
		
		protected $options_class = '\hiweb\components\Fields\Types\Repeat\Field_Repeat_Options';
		protected $last_value;
		/** @var Field_Repeat_Flex[] */
		private $flexes = [];
		private $unique_id;
		private $the_name;
		
		
		public function __construct( $field_ID ){
			parent::__construct( $field_ID );
		}
		
		
		/**
		 * @param null $set
		 * @return string
		 */
		public function get_unique_id( $set = null ){
			if( is_string( $set ) ) $this->unique_id = $set;
			return $this->unique_id;
		}
		
		
		public function get_css(){
			$R = [ HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.css', __DIR__ . '/Field_Repeat.css' ];
			foreach( $this->options()->get_cols() as $flex_id => $cols ){
				foreach( $cols as $col_id => $col ){
					$col_css = $col->Field()->get_css();
					if( is_array( $col_css ) ) $R = array_merge( $R, $col_css );
					elseif( is_string( $col_css ) ) $R[] = $col_css;
				}
			}
			return $R;
		}
		
		
		public function get_js(){
			$R = [ HIWEB_DIR_VENDOR . '/deepMerge/deepMerge.min.js', HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.js', __DIR__ . '/Field_Repeat.min.js' ];
			foreach( $this->options()->get_cols() as $flex_id => $cols ){
				foreach( $cols as $col_id => $col ){
					$col_js = $col->Field()->get_js();
					if( is_array( $col_js ) ) $R = array_merge( $R, $col_js );
					elseif( is_string( $col_js ) ) $R[] = $col_js;
				}
			}
			return $R;
		}
		
		
		public function admin_init(){
			foreach( $this->options()->get_cols() as $flex_id => $cols ){
				foreach( $cols as $col ){
					$col->Field()->admin_init();
				}
			}
		}
		
		
		/**
		 * @param string $id
		 * @return Field_Repeat_Flex
		 */
		public function get_flex( $id = '' ){
			$sanitize_id = Strings::sanitize_id( $id );
			if( !array_key_exists( $sanitize_id, $this->flexes ) ){
				$this->flexes[ $sanitize_id ] = new Field_Repeat_Flex( $this, $sanitize_id );
				if( $id != '' ) $this->flexes[ $sanitize_id ]->label( $id );
			}
			return $this->flexes[ $sanitize_id ];
		}
		
		
		/**
		 * @return Field_Repeat_Flex[]
		 */
		public function get_flexes(){
			return $this->flexes;
		}
		
		
		/**
		 * @param mixed|null $value
		 * @param bool       $update_meta_process
		 * @return array|mixed|null
		 */
		public function get_sanitize_admin_value( $value, $update_meta_process = false ){
			if( !is_array( $value ) ){
				return [];
			}
			return $value;
		}
		
		
		/**
		 * @return Field_Repeat_Options
		 */
		public function options(){
			return parent::options();
		}
		
		
		/**
		 * @param $value_array
		 * @return Field_Repeat_Value
		 */
		protected function Value( $value_array = null ){
			if( is_array( $value_array ) ){
				$key = md5( json_encode( $value_array ) );
				$this->last_value = CacheFactory::get( $key, __METHOD__, function(){
					return new Field_Repeat_Value( $this, func_get_arg( 0 ), $this->the_name );
				}, [ $value_array ] )->get_value();
			}
			if( !$this->last_value instanceof Field_Repeat_Value ){
				$this->last_value = new Field_Repeat_Value( $this );
			}
			return $this->last_value;
		}
		
		
		/**
		 * @return string
		 */
		protected function the_name(){
			return $this->the_name;
		}
		
		
		protected function get_head_html( $thead = true, $handle_title = '&nbsp;' ){
			ob_start();
			include __DIR__ . '/templates/head.php';
			return ob_get_clean();
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			$this->Value( $value );
			$this->the_name = $name;
			ob_start();
			include __DIR__ . '/templates/template.php';
			return ob_get_clean();
		}
		
		
		/**
		 * @return bool
		 */
		public function have_flex_cols(){
			return count( $this->options()->get_flex_ids() ) > 1 || !in_array( '', array_keys( $this->get_flexes() ) );
		}
		
	}