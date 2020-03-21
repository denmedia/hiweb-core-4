<?php

	namespace hiweb\admin\pages;


	use hiweb\console;


	abstract class page_abstract{


		protected $page_title = '⚙️ Options';
		protected $menu_title = '⚙️ Options';
		protected $capability = 'manage_options';
		protected $menu_slug = 'options';
		protected $function;
		protected $function_params = [];
		protected $use_default_form = true;


		public function __construct( $slug, $title ){
			if( !is_string( $slug ) ){
				console::debug_error( 'Вместо слуга [$parent_slug] передан не верный тип значения', $slug );
			} elseif( trim( $slug ) == '' ) {
				console::debug_error( 'Слуг [$parent_slug] пуст!' );
			} else {
				$this->menu_slug = $slug;
			}
			if( !is_string( $title ) ){
				console::debug_warn( 'Вместо слуга [$parent_slug] передан не верный тип значения', $title );
			} elseif( trim( $title ) == '' ) {
				console::debug_warn( 'Слуг [$parent_slug] пуст!' );
			} else {
				$this->menu_title = $title;
				$this->page_title = $title;
			}
		}


		/**
		 * @param $key
		 * @param null $val
		 * @return $this|null
		 */
		final protected function set( $key, $val = null ){
			if( is_null( $val ) ){
				if( property_exists( $this, $key ) ){
					return $this->{$key};
				} else {
					console::debug_warn( 'Попытка получить несуществующее свойство', $key );
					return null;
				}
			} else {
				if( property_exists( $this, $key ) ){
					$this->{$key} = $val;
				} else {
					console::debug_warn( 'Попытка установить несуществующее свойство', $key );
				}
				return $this;
			}
		}


		/**
		 * @return mixed
		 */
		final public function menu_slug(){
			return $this->menu_slug;
		}


		/**
		 * @param null $set
		 * @return $this|null
		 */
		public function page_title( $set = null ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return $this|null
		 */
		public function menu_title( $set = null ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return $this|null
		 */
		public function capability( $set = null ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @param array $params
		 * @return $this|null|string|array|callable
		 */
		public function function_page( $set = null, $params = [] ){
			if( !is_null( $set ) ) $this->function_params = $params;
			return $this->set( 'function', $set );
		}


		/**
		 * @param bool $set
		 * @return page_abstract|null
		 */
		public function use_default_form( $set = false ){
			return $this->set( __FUNCTION__, $set );
		}


		public function the_form(){
			if( $this->use_default_form ){
				include dirname( __DIR__ ) . '/templates/default-form.php';
			} else {
				return call_user_func( $this->function_page(), $this->function_params, $this );
			}
		}

	}