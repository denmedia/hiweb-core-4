<?php

	namespace hiweb\components\Includes;


	use hiweb\components\Includes\Css\Media;
	use hiweb\components\Includes\Css\Rel;
	use hiweb\core\Options\Options;
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
		 * Put file include to footer OR get bool is in footer
		 * @param null|bool $set
		 * @return Js|bool
		 */
		public function to_footer( $set = null ){
			return $this->_( 'footer', $set );
		}


		/**
		 * @param null|bool $set
		 * @return array|Css|mixed|null
		 */
		public function on_frontend( $set = null ){
			return $this->_( 'on_frontend', $set );
		}


		/**
		 * @param null|bool $set
		 * @return array|Css|mixed|null
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
		public function Rel(){
			if( !$this->_( 'rel' ) instanceof Rel ){
				$this->_( 'rel', new Rel( $this ) );
			}
			return $this->_( 'rel' );
		}


		/**
		 * @return Media
		 */
		public function Media(){
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
			$version = '';
			if( $this->Path()->is_local() ) $version = '?ver=' . filemtime( $this->Path()->File()->get_path() );
			return '<link ' . $this->Rel()() . ' id="' . $this->Path()->handle() . '" href="' . $this->Path()->Url()->get_clear() . $version . '" type="text/css" ' . $this->Media()() . ' />';
		}


		public function the_html(){
			echo $this->get_html();
		}

	}