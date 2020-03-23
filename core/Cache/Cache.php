<?php

	namespace hiweb\core\Cache;


	class Cache{

		private $value = null;
		private $value_callable_is_set = false;
		private $timestamp = null;
		private $variable_name = null;
		private $group_name = null;


		public function __construct( $value = null, $variable_name = null, $group_name = null ){
			$this->set( $value );
			$variable_name = (string)$variable_name;
			$group_name = (string)$group_name;
			if( $variable_name != '' ) $this->variable_name = $variable_name;
			if( $group_name != '' ) $this->group_name = $group_name;
		}


		/**
		 * @return null
		 */
		public function __invoke(){
			return $this->value;
		}


		/**
		 * @return string
		 */
		public function __toString(){
			return (string)$this->value;
		}


		/**
		 * @return string|null
		 */
		public function get_variable_name(){
			return $this->variable_name;
		}


		/**
		 * @return string|null
		 */
		public function get_group_name(){
			return $this->group_name;
		}


		/**
		 * @return null
		 */
		public function get(){
			return $this->value;
		}


		/**
		 * @param mixed $value
		 * @return Cache
		 */
		public function set( $value ){
			$this->value = $value;
			$this->timestamp = microtime( true );
			return $this;
		}


		/**
		 * @param callable $callable
		 * @param array    $func_args
		 * @return Cache
		 */
		public function set_callable( $callable, $func_args = [] ){
			if( is_callable( $callable ) && !$this->value_callable_is_set ){
				if( !is_array( $func_args ) ) $func_args = [ $func_args ];
				$this->value = call_user_func_array( $callable, $func_args );
				$this->timestamp = microtime( true );
				$this->value_callable_is_set = true;
			}
			return $this;
		}

	}