<?php
	
	namespace hiweb\components\Fields;
	
	
	use hiweb\components\Console\ConsoleFactory;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;
	
	
	class Field{
		
		use hidden_methods;
		/** @var string */
		private $ID;
		/** @var string */
		protected $global_ID;
		/** @var */
		protected $options_class = '\hiweb\components\Fields\Field_Options';
		
		
		public function __construct( $field_ID ){
			$this->ID = $field_ID;
			if( class_exists( $this->options_class ) ){
				$this->options_class = new $this->options_class( $this );
			}
			if( !$this->options_class instanceof Field_Options ){
				ConsoleFactory::add( 'this is not FieldOptions instance!', 'warn', __CLASS__, $this->options_class, true );
				$this->options_class = new \hiweb\components\Fields\Field_Options( $this );
			}
		}
		
		
		/**
		 * Return url, string or some array to css styles
		 * @return array|string
		 */
		public function get_css(){
			return [];
		}
		
		
		/**
		 * Return url, string or some array to js scripts
		 * @return array|string
		 */
		public function get_js(){
			return [];
		}
		
		
		/**
		 * @return Field_Options|mixed
		 */
		public function Options(){
			return CacheFactory::get( spl_object_id( $this ), __METHOD__, function(){
				if( $this->options_class instanceof Field_Options ){
					return $this->options_class;
				}
				elseif( class_exists( $this->options_class ) ){
					return new $this->options_class( $this );
				}
				else{
					ConsoleFactory::add( 'Error load options class for field', 'warn', __CLASS__, $this->options_class, true );
					return new Field_Options( $this );
				}
			} )->get_value();
		}
		
		
		/**
		 * Return field ID
		 * @return string
		 */
		public function ID(){
			return $this->ID;
		}
		
		
		/**
		 * Return field ID
		 * @alias ID()
		 * @return string
		 */
		public function get_ID(){
			return $this->ID();
		}
		
		
		/**
		 * Return field global ID
		 * @return string
		 */
		public function global_ID(){
			return $this->global_ID;
		}
		
		
		/**
		 * @param null $name
		 * @return null
		 */
		public function get_sanitize_admin_name( $name = null ){
			if( !is_string( $name ) || trim( $name ) == '' ) return $this->ID();
			return $name;
		}
		
		
		/**
		 * @param null|mixed $value
		 * @param bool       $update_meta_process - if TRUE, this is mean meta save process
		 * @return null|mixed
		 */
		public function get_sanitize_admin_value( $value, $update_meta_process = false ){
			return $value;
		}
		
		
		public function get_allow_save_field( $value ){
			return true;
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			$input_name = $this->get_sanitize_admin_name( $name );
			return '<div class="hiweb-field-type-default"><input type="text" name="' . htmlentities( $input_name ) . '" value="' . htmlentities( $this->get_sanitize_admin_value( $value ) ) . '" /></div>';
		}
		
		
	}