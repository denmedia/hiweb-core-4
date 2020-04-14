<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 01:46
	 */

	namespace hiweb\core\Paths;


	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\Paths\PathsFactory;
	use hiweb\core\Strings;


	class Path{

		/** @var string */
		protected $original_path = null;
		/** @var Url */
		protected $cache_Url;
		/** @var File */
		protected $cache_File;
		protected $handle;


		public function __construct( $path_or_url_or_handle = false ){
			if(array_key_exists($path_or_url_or_handle, wp_scripts()->registered)) {
				$path_or_url_or_handle = wp_scripts()->registered[$path_or_url_or_handle]->src;
				$this->handle = wp_scripts()->registered[$path_or_url_or_handle]->handle;
			}
			elseif(array_key_exists($path_or_url_or_handle, wp_styles()->registered)) {
				$path_or_url_or_handle = wp_styles()->registered[$path_or_url_or_handle]->src;
				$this->handle = wp_styles()->registered[$path_or_url_or_handle]->handle;
			}
			if( is_string( $path_or_url_or_handle ) ){
				$this->original_path = $path_or_url_or_handle;
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
			if( !$this->cache_Url instanceof Url ) $this->cache_Url = new Url( $this );
			return $this->cache_Url;
		}


		/**
		 * @return File
		 */
		public function File(){
			if( !$this->cache_File instanceof File ) $this->cache_File = new File( $this );
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
			if( !is_string( $this->handle ) || trim( $this->handle ) == '' ) {
				$path_to_handler = $this->is_local() ? join( '-', array_slice( $this->File()->dirs()->get(), - 3, 3 ) ) . '-' . $this->File()->basename() : $this->Url()->dirs()->join( '-' );
				$this->handle = trim( Strings::sanitize_id( $path_to_handler, '-' ), '_-' );
			}
			return $this->handle;
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