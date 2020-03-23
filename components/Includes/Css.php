<?php

	namespace hiweb\components\Includes;


	use hiweb\components\Includes\Css\Media;
	use hiweb\components\Includes\Css\Rel;
	use hiweb\core\Options;
	use hiweb\core\Paths\Path;


	class Css extends Options{

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
		 * @param null|bool $set
		 * @return array|Css|mixed|null
		 */
		public function on_frontend( $set = null ){
			return $this->_( 'is_frontend', $set );
		}


		/**
		 * @param null|bool $set
		 * @return array|Css|mixed|null
		 */
		public function on_admin( $set = null ){
			return $this->_( 'is_admin', $set );
		}


		/**
		 * @param bool $set
		 * @return Css
		 */
		public function set_admin( $set = true ){
			$this->_( 'is_admin', $set );
			return $this;
		}


		/**
		 * @param null|array $deeps
		 * @return Css
		 */
		public function deeps( $deeps = null ){
			return $this->_( 'deeps', $deeps );
		}


		/**
		 * @return Rel
		 */
		public function set_Rel(){
			if( !$this->_( 'rel' ) instanceof Rel ){
				$this->_( 'rel', new Rel( $this ) );
			}
			return $this->_( 'rel' );
		}


		/**
		 * @return Media
		 */
		public function set_Media(){
			if( !$this->_( 'media' ) instanceof Media ){
				$this->_( 'media', new Media( $this ) );
			}
			return $this->_( 'media' );
		}


		/**
		 * Get html <link href="...style.css"/>
		 * @return string
		 */
		public function get_html(){
			return '<link type="text/css" href="' . $this->Path()->Url()->get_clear() . '" ' . $this->set_Rel()() . ' ' . $this->set_Media()() . ' />';
		}


		public function the(){
			echo $this->get_html();
		}

	}