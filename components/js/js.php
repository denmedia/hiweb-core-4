<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05/12/2018
	 * Time: 00:00
	 */

	namespace hiweb;


	use hiweb\_js\options;
	use hiweb\paths\path;


	/**
	 * Class js
	 * @package hiweb\js
	 */
	class js{

		/** @var js[] */
		static $registred = [];
		/** @var js[] */
		static $queue = [];
		/** @var js[] */
		static $done = [];
		/** @var array */
		static $handles = [];


		/**
		 * Register file in queue
		 * @param $pathOrUrl
		 * @return options
		 */
		static function add( $pathOrUrl ){
			require_once __DIR__ . '/includes/options.php';
			require_once __DIR__ . '/includes/hooks.php';
			$handle = self::get_handle( $pathOrUrl );
			if( !array_key_exists( $handle, self::$registred ) ){
				$js = new js( $pathOrUrl );
				self::$queue[ $handle ] = $js;
				self::$registred[ $handle ] = $js;
				wp_register_script( $handle, $js->file()->get_url(), $js->options()->get_deeps(), filemtime( $js->file()->get_path() ), $js->options()->is_in_footer() );
				wp_enqueue_script( $handle );
			}
			return self::$registred[ $handle ]->options();
		}


		/**
		 * @param string $pathOrHandle
		 * @return mixed
		 */
		public static function get_handle( $pathOrHandle = 'jquery-core' ){
			if( !array_key_exists( $pathOrHandle, self::$handles ) ){
				global $wp_scripts;
				if( is_object( $wp_scripts ) && property_exists( $wp_scripts, 'registered' ) && is_array( $wp_scripts->registered ) && array_key_exists( $pathOrHandle, $wp_scripts->registered ) ){
					self::$handles[ $pathOrHandle ] = $pathOrHandle;//$wp_scripts->registered[ $pathOrHandle ]->src;
				} else {
					$path = Paths::get( $pathOrHandle );
					self::$handles[ $pathOrHandle ] = $path->handle();
					if( is_object( $wp_scripts ) && property_exists( $wp_scripts, 'registered' ) && is_array( $wp_scripts->registered ) ) foreach( $wp_scripts->registered as $handle => $file_data ){
						$test_path = Paths::get( $file_data->src );
						if( $path->get_path_relative() == $test_path->get_path_relative() ){
							self::$handles[ $pathOrHandle ] = $handle;
							break;
						}
					}
				}
			}
			return self::$handles[ $pathOrHandle ];
		}


		/**
		 * @param $js
		 * @return string|null
		 */
		static function get_queue_html( $js ){
			if( !$js instanceof js ) return '';
			$R = '';
			///
			foreach( $js->options()->get_deeps() as $handle ){
				if( array_key_exists( $handle, self::$queue ) ){
					$pre_file = self::$queue[ $handle ];
					self::$done[ $handle ] = self::$queue[ $handle ];
					unset( self::$queue[ $handle ] );
					$R .= self::get_queue_html( $pre_file );
				}
			}
			///
			self::$done[ $js->handle() ] = self::$queue[ $js->handle() ];
			unset( self::$queue[ $js->handle() ] );
			$R .= $js->html();
			return $R;
		}


		///ITEM

		public $pathOrUrl;
		protected $file;
		protected $options;


		public function __construct( $pathOrUrl ){
			$this->pathOrUrl = $pathOrUrl;
		}


		/**
		 * @return path
		 */
		public function file(){
			if( !$this->file instanceof path ){
				$this->file = Paths::get( $this->pathOrUrl );
			}
			return $this->file;
		}


		/**
		 * @return options
		 */
		public function options(){
			if( !$this->options instanceof options ){
				$this->options = new options( $this );
				$this->options->put_to_footer();
			}
			return $this->options;
		}


		/**
		 * Return (echo) link rel html
		 * @return null|string
		 */
		public function html(){
			return "<script {$this->options()->get_async()} data-handle=\"{$this->handle()}\" src=\"{$this->file()->get_url()}\"></script>\n";
		}


		/**
		 *
		 */
		public function the(){
			echo $this->html();
		}


		/**
		 * @return string
		 */
		public function handle(){
			return js::get_handle( $this->file()->get_path_relative() );
		}


	}