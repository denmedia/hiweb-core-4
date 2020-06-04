<?php
	
	namespace hiweb\components\Fields;
	
	
	use hiweb\components\Console\ConsoleFactory;
	use hiweb\core\hidden_methods;
	use hiweb\core\Strings;
	
	
	class Field{
		
		use hidden_methods;
		
		
		/** @var string */
		private $ID;
		/** @var string */
		protected $global_ID;
		/** @var */
		protected $options_class = '\hiweb\components\Fields\Field_Options';
		protected $id_empty = false;
		protected $debug_backtrace;
		
		
		public function __construct( $field_ID = null ){
			$this->debug_backtrace = debug_backtrace();
			if( !is_string( $field_ID ) ){
				$field_ID = strtolower( basename( str_replace( '\\', '/', get_called_class() ) ) ) . '_' . Strings::rand( 5 );
				$this->id_empty = true;
			}
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
		 * Init function once by admin page (where field is printed, not ajax)
		 */
		public function admin_init(){
			///
		}
		
		
		/**
		 * @return Field_Options|mixed
		 */
		public function options(){
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
		}
		
		
		/**
		 * Return field ID
		 * @return string
		 */
		public function id(){
			return $this->ID;
		}
		
		
		/**
		 * Return field ID
		 * @alias ID()
		 * @return string
		 */
		public function get_ID(){
			return $this->id();
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
			if( !is_string( $name ) || trim( $name ) == '' ) return $this->id();
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
		
		
		/**
		 * @param $value
		 * @return bool
		 */
		public function get_allow_save_field( $value = null ){
			return true && !$this->id_empty;
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			$input_name = $this->get_sanitize_admin_name( $name );
			return '<div class="hiweb-field-type-default"><input type="text" name="' . htmlentities( $input_name ) . '" value="' . htmlentities( $this->get_sanitize_admin_value( $value ) ) . '" /></div>';
		}
		
		
		/**
		 * @param null $wp_object
		 * @param null $object_id
		 * @param null $columns_name
		 * @return false|string
		 */
		public function get_admin_columns_html( $wp_object = null, $object_id = null, $columns_name = null ){
			ob_start();
			if( $wp_object instanceof \WP_Post ){
				$value = get_post_meta( $wp_object->ID, $this->id(), true );
			}
			elseif( $wp_object instanceof \WP_Term ){
				$value = get_term_meta( $wp_object->term_id, $this->id(), true );
			}
			elseif( $wp_object instanceof \WP_User ){
				$value = get_user_meta( $wp_object->ID, $this->id(), true );
			}
			elseif( $wp_object instanceof \WP_Comment ){
				$value = get_comment_meta( $wp_object->comment_ID, $this->id(), true );
			}
			echo '<div class="hiweb-' . Strings::sanitize_id( basename( str_replace( '\\', '/', get_called_class() ) ) ) . '-column-' . $this->id() . '">' . $value . '</div>';
			return ob_get_clean();
		}
		
		
	}