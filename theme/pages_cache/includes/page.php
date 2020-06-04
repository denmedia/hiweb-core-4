<?php

	namespace theme\pages_cache;


	use hiweb\urls;


	require_once __DIR__ . '/tools.php';
	require_once __DIR__ . '/cache.php';


	class page{

		static $pages = [];


		/**
		 * @param null $url
		 * @param bool $is_mobile
		 * @return page
		 */
		static function get_page( $url = null, $is_mobile = null ){
			if( !is_string( $url ) || empty( $url ) ){
				$url = tools::get_request_uri(false);
			}
			$url = tools::sanitize_url( $url );
			if( is_null( $is_mobile ) ) $is_mobile = tools::is_mobile();
			$url_key = $is_mobile ? 'mobile:' : '' . $url;
			if( !isset( self::$pages[ $url_key ] ) ){
				self::$pages[ $url_key ] = new page( $url, $is_mobile );
			}
			return self::$pages[ $url_key ];
		}


		////ITEM

		private $url;
		private $content = null;
		private $is_mobile = false;


		public function __construct( $url, $is_mobile = false ){
			$this->url = $url;
			$this->is_mobile = $is_mobile;
		}


		/**
		 * @param bool $is_mobile_prefix
		 * @return string
		 */
		public function get_url( $is_mobile_prefix = false ){
			return ( ( $is_mobile_prefix && $this->is_mobile ) ? 'mobile:' : '' ) . $this->url;
		}


		/**
		 * @return bool
		 */
		public function is_mobile(){
			return $this->is_mobile;
		}


		/**
		 * @param string $content_string - установить сожержимое страницы
		 * @return bool
		 */
		public function set_content( $content_string = null ){
			if( !options::is_allow_url( $this->get_url() ) ) return false;
			///
			$B = false;
			if( !is_string( $content_string ) ){
				if( $_SERVER['REMOTE_ADDR'] == gethostbyname( gethostname() ) ){
					//Do nothing: остановить создание кэша станицы в фоне, если текущий запрос уже фоновой
				} else {
					///DESKTOP
					if( function_exists( 'get_remote_data' ) ){
						$this->content = get_remote_data( PathsFactory::root( false ) . '/' . trim( $this->url, '/' ) . '?cache-disable' . ( $this->is_mobile() ? '&cache-mobile' : '' ) );
					} else {
						$this->content = file_get_contents( PathsFactory::root( false ) . '/' . trim( $this->url, '/' ) . '?cache-disable' . ( $this->is_mobile() ? '&cache-mobile' : '' ) );
					}
					$this->get_cache()->do_flush();
					$B = $this->get_cache()->set_content( $this->content );
				}
			} else {
				$this->content = $content_string;
				$this->get_cache()->do_flush();
				$B = $this->get_cache()->set_content( $this->content );
			}
			///
			return $B != false;
		}


		/**
		 * @return string
		 */
		public function get_content(){
			if( !is_string( $this->content ) ){
				$this->content = '';
				if( $this->get_cache()->is_exists() ){
					$this->content = $this->get_cache()->get_content();
				}
			}
			return $this->content;
		}


		/**
		 * @return cache
		 */
		public function get_cache(){
			return cache::get_cache( $this->url, $this->is_mobile() );
		}
	}