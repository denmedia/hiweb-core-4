<?php

	namespace theme\pages_cache;




	use hiweb\components\Date;
	use hiweb\components\HTML_CSS_JS_Minifier;
	use hiweb\core\Paths\PathsFactory;
	use hiweb\UsersFactory;
	
	
	require_once __DIR__ . '/tools.php';
	require_once __DIR__ . '/options.php';


	class cache{

		/** @var cache[] */
		static $caches = [];


		static function init(){
			self::auto_make_dir();
		}


		/**
		 * Возвращает путь до папки кэша страниц и данных
		 * @return string
		 */
		static function get_dir(){
			///MAKE CACHE DIR
			if( defined( 'WP_CONTENT_DIR' ) ){
				$base_dir = WP_CONTENT_DIR;
			} else {
				$base_dir = tools::base_dir() . '/wp-content';
			}
			return $base_dir . '/cache/hiweb-alpha-pages-cache/'.tools::sanitize_id( tools::get_base_url() );
		}


		static private function auto_make_dir(){
			$cache_dir = self::get_dir();
			if( !file_exists( $cache_dir ) ){
				mkdir( $cache_dir, 0755, true );
			}
		}


		/**
		 * @param      $url
		 * @param bool $is_mobile
		 * @return cache
		 */
		static function get_cache( $url, $is_mobile = false ){
			$url_key = ( $is_mobile ? 'mobile:' : '' ) . $url;
			if( !isset( self::$caches[ $url_key ] ) ){
				self::$caches[ $url_key ] = new cache( $url, $is_mobile );
			}
			return self::$caches[ $url_key ];
		}


		static function do_clear_all(){
			$R = [];
			foreach( PathsFactory::get_file( self::get_dir() )->get_sub_files() as $path => $file ){
				$R[ $path ] = @unlink( $path );
			}
			return $R;
		}


		///ITEM

		private $url;
		private $is_mobile = false;
		private $content = null;
		private $data = null;


		public function __construct( $url, $is_mobile = false ){
			$this->url = $url;
			$this->is_mobile = $is_mobile;
		}


		/**
		 * @return string
		 */
		public function get_url(){
			return $this->url;
		}


		/**
		 * @return bool
		 */
		public function is_mobile(){
			return $this->is_mobile;
		}


		/**
		 * @param string $extension
		 * @return string
		 */
		public function get_path( $extension = 'html' ){
			return self::get_dir() . '/' . tools::sanitize_id( $this->url ) . ( $this->is_mobile ? '.mobile.' : '.' ) . $extension;
		}


		/**
		 * @return bool
		 */
		public function is_exists(){
			return file_exists( $this->get_path() ) && is_file( $this->get_path() ) && is_readable( $this->get_path() );
		}


		/**
		 * Возвращает оставшееся время жизни кэша
		 * @return int
		 */
		public function get_time_left(){
			if( !$this->is_exists() ) return 0;
			return intval( $this->get_data()['time'] ) + intval( options::get( 'life-time', 18400 ) ) - time();
		}


		/**
		 * @return bool
		 */
		public function is_actual(){
			if( $this->is_exists() ){
				return $this->get_time_left() > 0;
			}
			return false;
		}


		/**
		 * @return string
		 */
		public function get_content(){
			if( !is_string( $this->content ) ){
				$this->content = '';
				if( $this->is_exists() ){
					$this->content = file_get_contents( $this->get_path( 'html' ) );
				}
			}
			return $this->content;
		}


		/**
		 * Set cache data
		 * @return bool|int
		 */
		public function set_data(){
			$data = [
				'user_agent' => $_SERVER['HTTP_USER_AGENT'],
				'request_uri' => $_SERVER['REQUEST_URI'],
				'current_url' => tools::get_current_url(),
				'login' => UsersFactory::get()->login(),
				'data_time' => Date::format(),
				'time' => time(),
				'doc_size' => strlen( $this->content )
			];
			return file_put_contents( $this->get_path( 'json' ), json_encode( $data ) );
		}


		/**
		 * @param string $content_string
		 * @return bool|int
		 */
		public function set_content( $content_string ){
			if(strlen($content_string) < 10) return false;
			$this->content = HTML_CSS_JS_Minifier::minify_html( $content_string );
			//$this->content = $content_string;
			self::auto_make_dir();
			$R = file_put_contents( $this->get_path( 'html' ), $this->content );
			///data
			$this->set_data();
			return $R;
		}


		/**
		 * @return array
		 */
		public function get_data(){
			if( !is_array( $this->data ) ){
				$this->data = [
					'user_agent' => '',
					'request_uri' => '',
					'current_url' => '',
					'login' => '',
					'data_time' => '',
					'time' => 0,
					'doc_size' => 0
				];
				if( file_exists( $this->get_path( 'json' ) ) && is_readable( $this->get_path( 'json' ) ) ){
					$raw_data = json_decode( file_get_contents( $this->get_path( 'json' ) ), true );
					if( json_last_error() == JSON_ERROR_NONE && is_array( $raw_data ) ){
						$this->data = array_merge( $this->data, $raw_data );
					}
				}
			}
			return $this->data;
		}


		/**
		 * Clear cache
		 */
		public function do_flush(){
			@unlink( $this->get_path( 'html' ) );
			@unlink( $this->get_path( 'json' ) );
		}


	}