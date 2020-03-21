<?php

	namespace hiweb\_js;


	use hiweb\js;


	class options{

		use \hiweb\traits\options;
		/** @var js */
		private $js;


		public function __construct( js $js ){
			$this->js = $js;
		}


		/**
		 * @return js
		 */
		public function _js(){
			return $this->js;
		}


		/**
		 * @return $this
		 */
		public function put_to_footer(){
			return $this->set_value('footer', true);
		}


		/**
		 * @return bool
		 */
		public function is_in_footer(){
			return $this->get_value('footer');
		}


		/**
		 * @return $this
		 */
		public function set_async(){
			return $this->set_value( 'async', 'async' );
		}


		/**
		 * @return $this
		 */
		public function set_defer(){
			return $this->set_value( 'async', 'defer' );
		}


		/**
		 * @return $this
		 */
		public function set_disable_async(){
			return $this->set_value( 'async', '' );
		}


		/**
		 * @return string
		 */
		public function get_async(){
			return $this->get_value( 'async' );
		}


		/**
		 * Add deeps styles
		 * @param null|string|array $deeps
		 * @return array
		 */
		public function add_deeps( $deeps = null ){
			$this->set_value( 'deeps', $deeps, true );
			return $this->get_value( 'deeps', [] );
		}


		/**
		 * @return array
		 */
		public function get_deeps(){
			return $this->get_value( 'deeps',[] );
		}

	}