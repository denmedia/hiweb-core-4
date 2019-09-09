<?php

	namespace hiweb\components\fields;


	use hiweb\components\fields\options\Options;
	use hiweb\core\arrays\Arrays;
	use hiweb\core\hidden_methods;


	class Field{

		use hidden_methods;

		/** @var Arrays */
		protected static $fields;


		/**
		 * @return Arrays
		 */
		static protected function Arrays(){
			if( !self::$fields instanceof Arrays ){
				self::$fields = Arrays::make();
			}
			return self::$fields;
		}


		/**
		 * @return array|Field[]
		 */
		static function get_fields(){
			return self::Arrays()->get();
		}


		/**
		 * @param $fieldObject
		 * @return Options
		 */
		static function add( $fieldObject ){
			if( $fieldObject instanceof Field ){
				self::Arrays()->push( $fieldObject->get_global_id(), $fieldObject );
				return $fieldObject->Options();
			}
			return null;
		}


		/////


		/** @var Options */
		protected $Options;
		protected $id;
		protected $global_id;


		public function __construct( $field_id ){
			$this->id = $field_id;
			$this->global_id = self::Arrays()->get_free_key( $field_id );
		}


		/**
		 * @return string
		 */
		public function get_id(){
			return $this->id;
		}


		/**
		 * @return string
		 */
		public function get_global_id(){
			return $this->global_id;
		}


		/**
		 * @return Options
		 */
		public function Options(){
			if( !$this->Options instanceof Options ) $this->Options = new Options( null, $this );
			return $this->Options;
		}


		/**
		 * print post manage columns
		 * @param $column_name
		 * @param $post_id
		 */
		public function the_post_columns( $column_name, $post_id ){
			include __DIR__ . '/types/_/html_post_columns.php';
		}


		/**
		 * Print field input
		 * @param $name
		 * @param $value
		 */
		public function the_form_input( $name, $value ){
			if(is_null($value)) $value = $this->Options()->_('default_value');
			?>
			<input name="<?= esc_attr( $name ) ?>" value="<?= esc_attr( $value ) ?>"/>
			<?php
		}


		public function save_post( $post_ID, $post, $update ){
			return update_post_meta( $post_ID, $this->get_id(), $_POST[ $this->get_id() ] );
		}

	}