<?php

	namespace hiweb\components\fields\options;


	use hiweb\components\fields\Field;


	class Options extends \hiweb\core\ArrayObject\Options{

		/** @var Field */
		private $Field;


		public function __construct(){
			parent::__construct( func_get_arg( 0 ) );
			$this->Field = func_get_arg( 1 );
		}


		/**
		 * @return Field
		 */
		public function Field(){
			return $this->Field;
		}


		/**
		 * @param null $default_value
		 * @return array|Options|mixed|null
		 */
		public function default_value( $default_value = null ){
			return $this->_( __FUNCTION__, $default_value );
		}


		/**
		 * @return Screen
		 */
		public function Screen(){
			if( !$this->_is_exists( 'screen' ) ){
				$this->set( 'screen', new Screen( $this ) );
			}
			return $this->get( 'screen' );
		}


		/**
		 * @param null $default_value
		 * @return array|Options|mixed|null
		 * @deprecated
		 * @alias ->default_value(...)
		 */
		public function VALUE( $default_value = null ){
			return $this->_( 'default_value', $default_value );
		}


		/**
		 * @alias     ->Screen()->...
		 * @return Screen
		 * @deprecated, use add_field_...()->Screen()->...
		 */
		public function LOCATION(){
			return $this->Screen();
		}

	}