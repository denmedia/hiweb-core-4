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
		/** @var Path_Url */
		protected $cache_Url;
		/** @var Path_File */
		protected $cache_File;
		protected $handle;
		
		
		/**
		 * Path constructor.
		 * @varsion 1.1
		 * @param bool $path_or_url_or_handle
		 */
		public function __construct( $path_or_url_or_handle = false ){
			$find_script = array_key_exists( $path_or_url_or_handle, wp_scripts()->registered );
			$find_style = array_key_exists( $path_or_url_or_handle, wp_styles()->registered );
			if( $find_script && wp_scripts()->registered[ $path_or_url_or_handle ] instanceof \stdClass){
				$path_or_url_or_handle = wp_scripts()->registered[ $path_or_url_or_handle ]->src;
				$this->handle = wp_scripts()->registered[ $path_or_url_or_handle ]->handle;
			}
			elseif( $find_style && wp_styles()->registered[ $path_or_url_or_handle ] instanceof \stdClass ){
				$path_or_url_or_handle = wp_styles()->registered[ $path_or_url_or_handle ]->src;
				$this->handle = wp_styles()->registered[ $path_or_url_or_handle ]->handle;
			}
			elseif(trim((string)$path_or_url_or_handle) == '') {
				$path_or_url_or_handle = PathsFactory::get_current_url();
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
		 * @return Path_Url
		 */
		public function url(){
			if( !$this->cache_Url instanceof Path_Url ) $this->cache_Url = new Path_Url( $this );
			return $this->cache_Url;
		}
		
		
		/**
		 * @return Path_File
		 */
		public function file(){
			if( !$this->cache_File instanceof Path_File ) $this->cache_File = new Path_File( $this );
			return $this->cache_File;
		}
		
		
		/**
		 * @return Path_Image
		 */
		public function image(){
			return $this->file()->Image();
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
			if( !is_string( $this->handle ) || trim( $this->handle ) == '' ){
				$path_to_handler = $this->is_local() ? join( '-', array_slice( $this->file()->dirs()->get(), - 3, 3 ) ) . '-' . $this->file()->basename() : $this->url()->dirs()->join( '-' );
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
				return $this->url()->domain() == $_SERVER['HTTP_HOST'];
			}
			else{
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
				return '/' . $this->url()->dirs()->join( '/' );
			}
			else{
				return str_replace( PathsFactory::root()->get_original_path(), '', $this->get_original_path() );
			}
		}
		
		
		/**
		 * @return string
		 */
		public function get_absolute_path(){
			return $this->file()->get_path();
		}
		
		
		/**
		 * @param null $return_universalScheme
		 * @return string
		 */
		public function get_url( $return_universalScheme = null ){
			return $this->url()->get( $return_universalScheme );
		}
		
	}