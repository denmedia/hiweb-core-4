<?php

	namespace hiweb\core\Cache;


	use hiweb\components\Console\ConsoleFactory;


	class Cache_CallbackValue{

		/** @var Cache */
		private $Cache;
		/** @var callable */
		private $function;
		/** @var array|mixed */
		private $func_args;
		///
		private $call_count = 0;


		public function __construct( Cache $Cache, $user_func = null, $func_args = [] ){
			$this->Cache = $Cache;
			$this->set_callable( $user_func, $func_args );
		}


		/**
		 * @return Cache
		 */
		public function Cache(){
			return $this->Cache;
		}


		/**
		 * Set function for generate cache value
		 * @param callable $user_func
		 * @param array    $func_args
		 * @return $this
		 */
		public function set_callable( $user_func, $func_args = [] ){
			if( is_callable( $user_func ) ){
				if( !is_array( $func_args ) ) $func_args = [ $func_args ];
				$this->function = $user_func;
				$this->func_args = $func_args;
			}
			return $this;
		}


		/**
		 * @return $this
		 */
		public function set_args(){
			$this->func_args = func_get_args();
			return $this;
		}


		/**
		 * @return bool
		 */
		public function is_callable(){
			return is_callable( $this->function );
		}


		/**
		 * Return the number of generations of value requested
		 * @return int
		 */
		public function get_count(){
			return $this->call_count;
		}


		/**
		 * Generate of value by callable function
		 * @return mixed|null
		 */
		public function get(){
			if( $this->is_callable() ){
				if( !is_array( $this->func_args ) ) $this->func_args = [ $this->func_args ];
				$this->call_count ++;
				$R = call_user_func_array( $this->function, $this->func_args );
				if( is_null( $R ) ){
					//ConsoleFactory::add( 'Callable function for [var: ' . $this->Cache()->get_variable_name() . ', group: ' . $this->Cache()->get_group_name() . '] return is NULL. Check it.', 'info', __CLASS__, [ $this->function, $this->func_args ], true );
				}
				return $R;
			}
			return null;
		}

	}