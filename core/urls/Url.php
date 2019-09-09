<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 03/12/2018
	 * Time: 22:16
	 */

	namespace hiweb\core\urls;


	use hiweb\core\hidden_methods;
	use hiweb\core\paths\Path;
	use hiweb\core\paths\Paths;


	/**
	 * Class url
	 * @version 1.0.0.0
	 * @package hiweb\urls
	 */
	class Url extends Path{

		private $url;
		private $prepare_url;
		private $dirs;
		private $dirs_str;
		private $params;
		private $params_str;
		private $schema;
		private $domain;
		private $base;
		private $prepare_data;
		private $root;


		use hidden_methods;


		public function __construct( $url ){
			parent::__construct( $url );
			$this->url = trim( $url );
			$this->prepare();
		}


		private function prepare(){
			if( !is_array( $this->prepare_data ) ){
				$this->prepare_data = [];
				$pattern = apply_filters( '\hiweb\urls\url::prepare-pattern', '/((?<schema>https?):\/\/|\/\/)?(?<domain>[\w\d\-\_]{2,}\.[\w\d\-\_]{1,}(?>\.[\w\d\-\_]+)?(?>\.[\w\d\-\_]+)?)?(?<dirs>[^\?]*)(?<params>.*)/i', $this );
				preg_match( $pattern, $this->url, $this->prepare_data );
				$this->prepare_data = apply_filters( '\hiweb\urls\url::prepare-data', $this->prepare_data, $this );
				///SCHEMA
				if( array_key_exists( 'schema', $this->prepare_data ) && $this->prepare_data['schema'] != '' ){
					$this->schema = $this->prepare_data['schema'];
				} else {
					$this->schema = ( ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? 'https' : 'http';
				}
				///DOMAIN
				if( array_key_exists( 'domain', $this->prepare_data ) && $this->prepare_data['domain'] != '' ){
					$this->domain = $this->prepare_data['domain'];
				} else {
					$this->domain = $_SERVER['HTTP_HOST'];
				}
				///DIRS
				if( array_key_exists( 'dirs', $this->prepare_data ) ){
					$this->dirs_str = ltrim( $this->prepare_data['dirs'], '/' );
				}
				///PARAMS
				if( array_key_exists( 'params', $this->prepare_data ) ){
					$this->params_str = ltrim( $this->prepare_data['params'], '?' );
				}
			}
		}


		/**
		 * Prepare URL
		 * @param null|bool $use_noscheme
		 * @return mixed
		 */
		public function get( $use_noscheme = null ){
			if( !is_string( $this->prepare_url ) ){
				$this->prepare_url = apply_filters( '\hiweb\urls\url::prepare-first', $this->base( $use_noscheme ) . ( $this->has_dirs() ? '/' . $this->dirs( false ) : '' ) . ( $this->has_params() ? '?' . $this->params( false ) : '' ), $this );
			}
			return apply_filters( '\hiweb\urls\url::prepare', $this->prepare_url, $this );
		}


		public function parse(){
			return [
				'url' => $this->get(),
				'base' => $this->base(),
				'schema' => $this->schema(),
				'domain' => $this->domain(),
				'dirs' => $this->dirs( true ),
				'params' => $this->params( true ),
				'is_local' => $this->is_local(),
				'is_ssl' => $this->is_ssl(),
				'path' => $this->get_path()
			];
		}


		/**
		 * @param null|bool $use_noscheme
		 * @return string
		 */
		public function base( $use_noscheme = null ){
			$key = json_encode( $use_noscheme );
			if( !is_string( $this->base[ $key ] ) ){
				if( !is_bool( $use_noscheme ) ) $use_noscheme = Urls::$use_noscheme_urls;
				$this->base[ $key ] = ( $use_noscheme ? '//' : $this->schema() . '://' ) . $this->domain();
			}
			return apply_filters( '\hiweb\urls\url::base', $this->base[ $key ], $use_noscheme, $this );
		}


		/**
		 * @return string
		 */
		public function schema(){
			if( !is_string( $this->schema ) || $this->schema == '' ){
				$this->schema = 'http';
			}
			return apply_filters( '\hiweb\urls\url::schema', $this->schema, $this );
		}


		/**
		 * @return bool
		 */
		public function is_ssl(){
			return apply_filters( '\hiweb\urls\url::is_ssl', $this->schema() === 'https' );
		}


		/**
		 * @return string
		 */
		public function domain(){
			return apply_filters( '\hiweb\urls\url::domain', $this->domain, $this );
		}


		/**
		 * @version 1.1
		 * @param bool $return_array
		 * @return string[]|string
		 */
		public function dirs( $return_array = true ){
			if( !is_array( $this->dirs ) ){
				$this->dirs = explode( '/', trim($this->dirs_str,'/') );
			}
			return $return_array ? $this->dirs : $this->dirs_str;
		}


		/**
		 * @param int $index
		 * @return string|null
		 */
		public function dir( $index = 0 ){
			return array_key_exists( $index, $this->dirs( true ) ) ? $this->dirs[ $index ] : null;
		}


		/**
		 * @return bool
		 */
		public function has_dirs(){
			return count( $this->dirs( true ) ) > 0;
		}


		/**
		 * @param bool $return_array
		 * @return array|string
		 */
		public function params( $return_array = true ){
			if( !is_array( $this->params ) ){
				$this->params = [];
				if( !is_string( $this->params_str ) ) $this->params_str = '';
				if( strlen( $this->params_str ) > 0 ){
					foreach( explode( '&', $this->params_str ) as $pair ){
						list( $key, $val ) = explode( '=', $pair );
						$this->params[ $key ] = $val;
					}
				}
			}
			return $return_array ? apply_filters( '\hiweb\urls\url::params-array', $this->params ) : apply_filters( '\hiweb\urls\url::params-string', $this->params_str );
		}


		/**
		 * @param $key
		 * @return null
		 */
		public function param( $key ){
			return array_key_exists( $key, $this->params() ) ? $this->params[ $key ] : null;
		}


		/**
		 * @return bool
		 */
		public function has_params(){
			return count( $this->params( true ) ) > 0;
		}


		/**
		 * @return bool
		 */
		public function is_local(){
			return $this->domain() == Urls::get()->domain();
		}


		/**
		 * Возвращает массив пересекающихся папок в URL, с учетом их порядка
		 * @param $url
		 * @return array
		 */
		public function get_dirs_intersect( $url ){
			$R = [];
			$url = Urls::get( $url );
			foreach( $url->dirs( true ) as $index => $dir ){
				if( $this->dir( $index ) != $dir ){
					break;
				}
				$R[] = $dir;
			}
			return $R;
		}


		/**
		 * @param $url
		 * @return bool
		 */
		public function is_dirs_intersect( $url ){
			$url = trim($url, '/');
			$url_dirs = count( Urls::get( $url )->dirs() );
			return ( count( $this->get_dirs_intersect( $url ) ) >= $url_dirs );
		}


		/**
		 * @param null|bool $use_noscheme
		 * @return string
		 */
		public function root( $use_noscheme = null ){
			$key = json_encode( $use_noscheme );
			if( !is_string( $this->root[ $key ] ) ){
				$this->root[ $key ] = $this->base( $use_noscheme );
				if( $this->is_local() ){
					$root = ltrim( Paths::root(), '/' );
					$query = ltrim( str_replace( '\\', '/', dirname( $_SERVER['PHP_SELF'] ) ), '/' );
					$rootArr = [];
					$queryArr = [];
					foreach( array_reverse( explode( '/', $root ) ) as $dir ){
						$rootArr[] = rtrim( $dir . '/' . end( $rootArr ), '/' );
					}
					foreach( explode( '/', $query ) as $dir ){
						$queryArr[] = ltrim( end( $queryArr ) . '/' . $dir, '/' );
					}
					$rootArr = array_reverse( $rootArr );
					$queryArr = array_reverse( $queryArr );
					$r = '';
					foreach( $queryArr as $dir ){
						foreach( $rootArr as $rootDir ){
							if( $dir == $rootDir ){
								$r = $dir;
								break 2;
							}
						}
					}
					$this->root[ $key ] = rtrim( $this->base( $use_noscheme ) . '/' . $r, '/' );
				}
			}
			return $this->root[ $key ];
		}


		/**
		 * Return only URL, without params
		 * @param null|bool $use_noscheme
		 * @return string
		 */
		public function get_clear( $use_noscheme = null ){
			return $this->base( $use_noscheme ) . ( $this->has_dirs() ? '/' . $this->dirs( false ) : '' );
		}


		/**
		 * @param $params
		 * @return Url
		 */
		public function set_params( $params ){
			$params = get_array( $this->params( true ) )->merge( $params );
			$new_url = $this->get_clear() . ( !$params->is_empty() ? '?' . $params->get_param_html_tags() : '' );
			return Urls::get( $new_url );
		}

	}