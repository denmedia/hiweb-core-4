<?php


	use hiweb\core\Paths\PathsFactory;
	use theme\_minify\template;
	use theme\minify;


	class cache{


		static function get_dir(){
			///MAKE CACHE DIR
			if( defined( 'WP_CONTENT_DIR' ) ){
				$base_dir = WP_CONTENT_DIR;
			} else {
				$base_dir = PathsFactory::get_root_path() . '/wp-content';
			}
			return $base_dir . '/cache/hiweb-alpha-minify/site-' . get_current_blog_id();
		}


		static private function do_make_dir(){
			$cache_dir = self::get_dir();
			if( !file_exists( $cache_dir ) ){
				return mkdir( $cache_dir, 0755, true );
			}
			return true;
		}


		/**
		 * @param $id
		 * @return bool|mixed
		 */
		static function get_template_path_by_id( $id ){
			$path = self::get_dir() . '/' . $id . '.json';
			if( file_exists( $path ) && is_file( $path ) && is_readable( $path ) ){
				$data = json_decode( file_get_contents( $path ), true );
				if( json_last_error() == JSON_ERROR_NONE && is_array( $data ) && isset( $data['template_path'] ) ) return $data['template_path'];
			}
			return false;
		}


		/**
		 * @return array
		 */
		static function do_clear_all(){
			$R = [];
			foreach( PathsFactory::get_file( self::get_dir() )->get_sub_files() as $path => $file ){
				$R[ $path ] = @unlink( $path );
			}
			return $R;
		}


		/**
		 * @return array
		 */
		static function do_clear_css(){
			$R = [];
			foreach( PathsFactory::get_file( self::get_dir() )->get_sub_files('css') as $path => $file ){
				$R[ $path ] = @unlink( $path );
			}
			return $R;
		}


		/**
		 * @return array
		 */
		static function do_clear_js(){
			$R = [];
			foreach( PathsFactory::get_file( self::get_dir() )->get_sub_files('js') as $path => $file ){
				$R[ $path ] = @unlink( $path );
			}
			return $R;
		}


		/** @var template */
		private $template;
		/** @var array */
		public $data;


		public function __construct( template $template ){
			self::do_make_dir();
			$this->template = $template;
			$this->data_load();
		}


		private function data_load(){
			$this->data = [
				'id' => $this->get_id(),
				'template_path' => $this->template->get_path()
			];
			if( $this->is_exists( 'json' ) ){
				$cache_data = json_decode( file_get_contents( $this->get_cache_path( 'json' ) ), true );
				if( json_last_error() == JSON_ERROR_NONE ){
					$this->data = array_merge( $this->data, $cache_data );
				}
			}
		}


		/**
		 * @return string
		 */
		public function get_id(){
			return md5( $this->template->get_path() );
		}


		/**
		 * @param string $extension
		 * @return string
		 */
		public function get_cache_path( $extension = 'json' ){
			return self::get_dir() . '/' . $this->get_id() . '.' . ( ( $extension != 'json' && is_user_logged_in() ) ? 'logged.' : '' ) . ( ( $extension != 'json' && wp_is_mobile() ) ? 'mobile.' : '' ) . $extension;
		}


		/**
		 * @param string $extension
		 * @return bool
		 */
		public function is_exists( $extension = 'json' ){
			$path = $this->get_cache_path( $extension );
			return file_exists( $path ) && is_readable( $path ) && is_file( $path );
		}


		/**
		 * @param        $data
		 * @param string $extension
		 * @param null   $md5sum - хэш сумма для файлов кэша
		 * @return bool
		 */
		public function do_update( $data, $extension = 'json', $md5sum = null ){
			if( is_array( $data ) ) $data = json_encode( $data );
			$tmp_path = $this->get_cache_path( $extension . '.tmp' );
			$path = $this->get_cache_path( $extension );
			$B = file_put_contents( $tmp_path, $data );
			if( $B !== false && filesize( $tmp_path ) > 2 ){
				if( minify::$debug ) console_info( 'do_update: ' . $extension, '\theme\_minify\cache' );
				if( $extension !== 'json' ){
					$this->data[ $path ] = [
						'md5sum' => is_string( $md5sum ) ? $md5sum : md5( $data ),
						'time' => time(),
						'date' => \hiweb\components\Date::format( time() )
					];
					$this->do_update( $this->data, 'json' );
				}
				@unlink( $path );
				return rename( $tmp_path, $path );
			} else {
				if( minify::$debug ) console_error( 'do_update - error update: ' . $extension, '\theme\_minify\cache' );
				@unlink( $tmp_path );
				return false;
			}
		}


		/**
		 * @param string $extension
		 * @return array
		 */
		public function get_file_data( $extension = 'json' ){
			$path = $this->get_cache_path( $extension );
			$data = [ 'md5sum' => '', 'time' => 0, 'date' => '' ];
			if( array_key_exists( $path, $this->data ) ){
				$data = array_merge( $data, $this->data[ $path ] );
			}
			return $data;
		}


		/**
		 * @return array
		 */
		public function get_data(){
			return $this->data;
		}


		/**
		 * @param      $key
		 * @param null $default
		 * @return mixed|null
		 */
		public function get_data_value( $key, $default = null ){
			return array_key_exists( $key, $this->data ) ? $this->data[ $key ] : $default;
		}

	}