<?php

	namespace hiweb\core\Options;


	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\hidden_methods;
	use hiweb\core\Options\Options_Once;


	/**
	 * Используется создания опций или субопций объекта
	 * Class Options
	 * @package hiweb\core
	 */
	abstract class Options{

		use hidden_methods;

		/** @var ArrayObject */
		private $Options;
		/** @var Options|null */
		private $parent_OptionsObject;


		public function __construct( $parent_OptionsObject = null ){
			$this->Options = new ArrayObject( [] );
			///Set Parent Options Object
			if( $parent_OptionsObject instanceof Options ){
				$this->parent_OptionsObject = $parent_OptionsObject;
			}
		}


		/**
		 * @return Options|null|$this
		 */
		protected function getParent_OptionsObject(){
			if( $this->parent_OptionsObject instanceof Options ){
				return $this->parent_OptionsObject;
			}
			return null;
		}


		/**
		 * Return root Options Object
		 * @return Options
		 */
		protected function getRoot_OptionsObject(){
			if( $this->parent_OptionsObject instanceof Options ){
				return $this->parent_OptionsObject->getRoot_OptionsObject();
			} else return $this;
		}


		/**
		 * @param $option_key
		 * @param $value
		 * @return $this
		 */
		protected function set( $option_key, $value ){
			$this->Options->set_value( $option_key, $value );
			return $this;
		}


		/**
		 * @param null $option_key
		 * @param null $default
		 * @return array|mixed|null
		 */
		protected function get( $option_key = null, $default = null ){
			return $this->Options->_( $option_key, $default );
		}


		/**
		 * Remove option by key
		 * @aliace \hiweb\core\ArrayObject\Options::unset
		 * @param $option_key
		 * @return $this
		 */
		protected function remove( $option_key ){
			$this->Options->unset_key( $option_key );
			return $this;
		}


		/**
		 * Unset option by key to NULL
		 * @param $option_key
		 */
		protected function unset( $option_key ){
			$this->set( $option_key, null );
		}


		/**
		 * @return ArrayObject
		 */
		protected function options_ArrayObject(){
			return $this->Options;
		}


		/**
		 * @param      $option_key
		 * @param null $value
		 * @param null $default
		 * @return $this|array|mixed|null
		 */
		public function _( $option_key, $value = null, $default = null ){
			if( is_null( $value ) ){
				return $this->Options->_( $option_key, $default );
			} else {
				return $this->set( $option_key, $value );
			}
		}


		/**
		 * @param $option_key
		 * @return bool
		 */
		public function _is_exists( $option_key ){
			return $this->options_ArrayObject()->is_key_exists( $option_key );
		}


		/**
		 * Collect options and sub-options to array
		 * @return array
		 */
		public function _get_optionsCollect(){
			$R = [];
			foreach( $this->options_ArrayObject()->get() as $key => $value ){
				if( $value instanceof Options_Once ){
					$R[ $key ] = $value->get();
				} elseif( $value instanceof Options ) {
					$R = array_merge( $R, [ $key => $value->_get_optionsCollect() ] );
				} else {
					$R[ $key ] = $value;
				}
			}
			return $R;
		}


		/**
		 * @param array|mixed $arrayOrOnceData
		 */
		public function _set_optionsCollect( $arrayOrOnceData ){
			foreach( $arrayOrOnceData as $key => $value ){
				if( $this->_is_exists( $key ) && $this->_( $key ) instanceof Options ){
					$this->_( $key )->_set_optionsCollect( $value );
				} elseif( method_exists( $this, $key ) ) {
					call_user_func( [ $this, $key ], $value );
				} else {
					$this->_( $key, $value );
				}
			}
		}


		/**
		 * @return array
		 */
		public function __invoke(){
			return $this->_get_optionsCollect();
		}

	}