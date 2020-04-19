<?php
	
	namespace hiweb\components\Fields\Types\Repeat;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\Strings;
	
	
	class Field_Repeat extends Field{
		
		protected $options_class = '\hiweb\components\Fields\Types\Repeat\Field_Repeat_Options';
		protected $last_value;
		/** @var Field_Repeat_Flex[] */
		private $flexes = [];
		
		
		public function get_css(){
			$R = [ HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.css', __DIR__ . '/field-repeat.css' ];
			foreach( $this->Options()->get_cols() as $flex_id => $cols ){
				foreach( $cols as $col_id => $col ){
					$col_css = $col->Field()->get_css();
					if( is_array( $col_css ) ) $R = array_merge( $R, $col_css );
					elseif( is_string( $col_css ) ) $R[] = $col_css;
				}
			}
			return $R;
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
		
		
		public function get_js(){
			$R = [ HIWEB_DIR_VENDOR . '/deepMerge/deepMerge.min.js', HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.js', __DIR__ . '/field-repeat.min.js' ];
			foreach( $this->Options()->get_cols() as $flex_id => $cols ){
				foreach( $cols as $col_id => $col ){
					$col_js = $col->Field()->get_js();
					if( is_array( $col_js ) ) $R = array_merge( $R, $col_js );
					elseif( is_string( $col_js ) ) $R[] = $col_js;
				}
			}
			return $R;
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
			else foreach( $value as $index => $row ){
				if( !is_array( $row ) ) $row = [];
				$row = array_merge( [ '_flex_row_id' => '' ], $row );
				$value[ $index ] = $row;
			}
			return $value;
		}
		
		
		/**
		 * @return Field_Repeat_Options
		 */
		public function Options(){
			return parent::Options();
		}
		
		
		/**
		 * @param $value_array
		 * @return Field_Repeat_Value
		 */
		public function Value( $value_array = null ){
			if( is_array( $value_array ) ){
				$key = md5( json_encode( $value_array ) );
				$this->last_value = CacheFactory::get( $key, __METHOD__, function(){
					return new Field_Repeat_Value( $this, func_get_arg( 0 ) );
				}, [ $value_array ] )->get_value();
			}
			if( !$this->last_value instanceof Field_Repeat_Value ){
				console_warn( 'not value set' );
				$this->last_value = new Field_Repeat_Value( $this );
			}
			return $this->last_value;
		}
		
		
		protected function get_head_html( $thead = true, $handle_title = '&nbsp;' ){
			ob_start();
			include __DIR__ . '/templates/head.php';
			return ob_get_clean();
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			$this->Value( $value );
			ob_start();
			include __DIR__ . '/templates/template.php';
			return ob_get_clean();
		}
		
		
		/**
		 * @return bool
		 */
		public function have_flex_cols(){
			return count( $this->Options()->get_flex_ids() );
		}
		
	}