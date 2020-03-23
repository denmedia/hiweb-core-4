<?php

	namespace hiweb\components\Includes;


	use hiweb\core\Options;
	use hiweb\core\Paths\Path;


	class Js extends Options{

		/** @var Path */
		private $Path;


		public function __construct( Path $Path ){
			parent::__construct();
			$this->Path = $Path;
		}


		/**
		 * @return Path
		 */
		public function Path(){
			return $this->Path;
		}


		/**
		 * Put file include to footer OR get bool is in footer
		 * @param null|bool $set
		 * @return Js|bool
		 */
		public function to_footer( $set = null ){
			return $this->_( 'footer', $set );
		}


		/**
		 * @param null|bool $set
		 * @return array|Js|mixed|null
		 */
		public function on_frontend( $set = null ){
			return $this->_( 'is_frontend', $set );
		}


		/**
		 * @param null|bool $set
		 * @return array|Js|mixed|null
		 */
		public function on_admin( $set = null ){
			return $this->_( 'is_admin', $set );
		}


		/**
		 * @param bool $set
		 * @return Js
		 */
		public function set_async( $set = true ){
			if( $set ) $this->_( 'async', 'async' ); else $this->remove( 'async' );
			return $this;
		}


		/**
		 * @param bool $set
		 * @return Js
		 */
		public function set_defer( $set = true ){
			if( $set ) $this->_( 'async', 'defer' ); else $this->remove( 'async' );
			return $this;
		}


		/**
		 * @param array $deeps
		 * @return Js
		 */
		public function set_deeps( $deeps = [] ){
			$this->_( 'deeps', $deeps );
			return $this;
		}


		public function get_html(){
			return '<script ' . $this->_( 'async' ) . ' data-handle="' . $this->Path()->handle() . '" src="' . $this->Path()->Url()->get() . '"></script>';
		}


		public function the(){
			echo $this->get_html();
		}

	}