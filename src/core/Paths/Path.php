<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 01:46
	 */

	namespace hiweb\core\Paths;


	use hiweb\core\PathsFactory;
	use hiweb\core\Strings;


	class Path{

		/** @var string */
		protected $original_path = null;
		/** @var Url */
		protected $cache_Url;
		/** @var File */
		protected $cache_File;


		public function __construct( $path_or_url = false ){
			if( is_string( $path_or_url ) ){
				$this->original_path = $path_or_url;
			}
		}


		/**
		 * @return mixed
		 */
		public function __toString(){
			return $this->get_original_path();
		}


		public function __invoke(){
			return $this->get_original_path();
		}


		/**
		 * @return Url
		 */
		public function Url(){
			if( !$this->cache_Url instanceof Url ){
				$this->cache_Url = new Url( $this );
			}
			return $this->cache_Url;
		}


		/**
		 * @return File
		 */
		public function File(){
			if( !$this->cache_File instanceof File ){
				$this->cache_File = new File( $this );
			}
			return $this->cache_File;
		}


		/**
		 * @return Image
		 */
		public function Image(){
			return $this->File()->Image();
		}


		/**
		 * Return raw original path
		 * @return string
		 */
		public function get_original_path(){
			return $this->original_path;
		}


		/**
		 * @return bool|string|int
		 * @version 1.0
		 */
		public function handle(){
			return trim( Strings::sanitize_id( basename( $this->File()->dirname() ) . '/' . $this->File()->basename(), '-' ), '_-' );
		}


		/**
		 * @return bool
		 * @version 1.0
		 */
		public function is_relative(){
			return is_string( $this->original_path ) && ( strpos( $this->original_path, PathsFactory::get_root_path() ) !== 0 && !$this->is_url() );
		}


		/**
		 * @return bool
		 * @version 1.0
		 */
		public function is_absolute(){
			return is_string( $this->original_path ) && ( strpos( $this->original_path, PathsFactory::get_root_path() ) === 0 && !$this->is_url() );
		}


		/**
		 * @return bool
		 * @version 1.0
		 */
		public function is_local(){
			if( is_string( $this->get_original_path() ) && $this->is_url() ){
				return $this->Url()->domain() == $_SERVER['HTTP_HOST'];
			} else {
				return $this->is_absolute() || $this->is_relative();
			}
		}


		/**
		 * Возвращает TRUE, если передан URL
		 * @return mixed
		 */
		public function is_url(){
			return ( is_string( $this->get_original_path() ) && preg_match( '/^([\w]+:)?\/\/[а-яА-ЯЁёa-zA-Z0-9_\-.]+/im', $this->get_original_path() ) > 0 );
		}


		/**
		 * @param bool $return_params - return params, if this is url
		 * @return string
		 * @version 1.1
		 */
		public function get_path_relative( $return_params = false ){
			if( $this->is_url() ){
				return '/' . $this->Url()->dirs()->join( '/' );
			} else {
				return str_replace( PathsFactory::root()->get_original_path(), '', $this->get_original_path() );
			}
		}

	}