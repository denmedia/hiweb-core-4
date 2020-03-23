<?php

	namespace hiweb\components\Includes;


	use hiweb\core\Options\Options;
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
			return $this->_( 'on_frontend', $set );
		}


		/**
		 * @param null|bool $set
		 * @return array|Js|mixed|null
		 */
		public function on_admin( $set = null ){
			return $this->_( 'on_admin', $set );
		}


		/**
		 * @param null|bool $set
		 * @return array|Js|mixed|null
		 */
		public function on_login( $set = null ){
			return $this->_( 'on_login', $set );
		}


		/**
		 * @param bool $set
		 * @return Js
		 */
		public function async( $set = true ){
			if( $set ) return $this->_( 'async', 'async' ); else $this->remove( 'async' );
			return $this;
		}


		/**
		 * @param bool $set
		 * @return Js
		 */
		public function defer( $set = true ){
			if( $set ) return $this->_( 'async', 'defer' ); else $this->remove( 'async' );
			return $this;
		}


		/**
		 * @param array $deeps
		 * @return Js
		 */
		public function deeps( $deeps = [] ){
			return $this->_( 'deeps', $deeps );
		}


		public function get_html(){
			return '<script type="text/javascript" src="' . $this->Path()->Url()->get() . '" ' . $this->_( 'async' ) . ' data-handle="' . $this->Path()->handle() . '"></script>';
		}


		public function the_html(){
			echo $this->get_html();
		}

	}