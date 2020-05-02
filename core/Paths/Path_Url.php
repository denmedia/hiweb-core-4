<?php

	namespace hiweb\core\Paths;


	use hiweb\components\Dump;
	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\hidden_methods;
	use hiweb\core\Paths\PathsFactory;
	use hiweb\core\urls\Urls;


	class Path_Url{

		use hidden_methods;

		/** @var Path */
		private $Path;

		private $url;
		private $prepare_url;
		/** @var ArrayObject */
		private $dirs;
		private $dirs_str;
		private $params;
		private $params_str;
		private $schema;
		private $domain;
		private $base = [];
		private $prepare_data;
		private $root;


		public function __construct( Path $Path ){
			$this->Path = $Path;
			$this->url = trim( $Path->get_original_path() );
			$this->prepare();
		}


		/**
		 * @return Path
		 */
		public function Path(){
			return $this->Path;
		}


		/**
		 * @return Path_File
		 */
		public function File(){
			return $this->Path()->File();
		}


		/**
		 * Do prepare and base parse URL
		 */
		private function prepare(){
			if( !is_array( $this->prepare_data ) ){
				$this->prepare_data = [];
				if( strpos( $this->url, PathsFactory::get_root_path() ) === 0 ) $this->url = str_replace( PathsFactory::get_root_path(), '', $this->url );
				$pattern = apply_filters( '\hiweb\urls\url::prepare-pattern', '/((?<schema>[\w]+?):\/\/|\/\/)?(?<domain>[\w\d\-\_]{2,}\.[\w\d\-\_]{1,}(?>\.[\w\d\-\_]+)?(?>\.[\w\d\-\_]+)?)?(?<dirs>[^\?]*)(?<params>.*)/i', $this );
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
		 * @return string
		 */
		public function schema(){
			if( !is_string( $this->schema ) || $this->schema == '' ){
				$this->schema = 'http';
			}
			return apply_filters( '\hiweb\urls\url::schema', $this->schema, $this );
		}


		/**
		 * @version 1.1
		 * @param null|bool $use_noscheme
		 * @return string
		 */
		public function base( $use_noscheme = null ){
			$key = json_encode( $use_noscheme );
			if(!isset($this->base[$key]) || !is_string( $this->base[ $key ] ) ){
				if( !is_bool( $use_noscheme ) ) $use_noscheme = PathsFactory::$use_universal_schema_urls;
				$this->base[ $key ] = ( $use_noscheme ? '//' : $this->schema() . '://' ) . $this->domain();
			}
			return apply_filters( '\hiweb\urls\url::base', $this->base[ $key ], $use_noscheme, $this );
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
		 * @param null $return_universalScheme
		 * @return string
		 */
		public function get( $return_universalScheme = null ){
			if( !is_string( $this->prepare_url ) ){
				$this->prepare_url = apply_filters( '\hiweb\urls\url::prepare-first', $this->base( $return_universalScheme ) . ( $this->dirs()->is_empty() ? '' : '/' . $this->dirs()->join( '/' ) ) . ( $this->params()->is_empty() ? '' : '?' . $this->params()->join( '&' ) ), $this );
			}
			return apply_filters( '\hiweb\urls\url::prepare', $this->prepare_url, $this );
		}


		/**
		 * Return only URL, without params
		 * @param null|bool $use_universalScheme
		 * @return string
		 */
		public function get_clear( $use_universalScheme = null ){
			return $this->base( $use_universalScheme ) . ( !$this->dirs()->is_empty() ? '/' . $this->dirs()->join( '/' ) : '' );
		}


		/**
		 * Return url dirs ArrayObject
		 * @return ArrayObject
		 */
		public function dirs(){
			if( !$this->dirs instanceof ArrayObject ){
				$this->dirs = new ArrayObject( explode( '/', trim( $this->dirs_str, '/' ) ) );
			}
			return $this->dirs;
		}


		/**
		 * Return params ArrayObject
		 * @return ArrayObject
		 */
		public function params(){
			if( !$this->params instanceof ArrayObject ){
				$this->params = new ArrayObject();
				if( !is_string( $this->params_str ) ) $this->params_str = '';
				if( strlen( $this->params_str ) > 0 ){
					foreach( explode( '&', $this->params_str ) as $pair ){
						[ $key, $val ] = explode( '=', $pair );
						$this->params->push( $key, $val );
					}
				}
			}
			return $this->params;
		}
		
		/**
		 * @param $params
		 * @return $this
		 */
		public function set_params($params){
			if(is_array($params)) foreach($params as $key => $val) {
				$this->params()->push($key, $val);
			}
			return $this;
		}


		/**
		 * Возвращает массив пересекающихся папок в URL, с учетом их порядка
		 * @param $haystackUrl
		 * @return array
		 */
		public function get_dirs_intersect( $haystackUrl ){
			$R = [];
			$this->dirs()->Rows()->each( function( $index, $dir ){
				if( $this->dirs()->get_value_by_index( $index ) != $dir ){
					return;
				}
				$R[] = $dir;
			} );
			return $R;
		}


		/**
		 * Return TRUE, if $haystackUrl
		 * @param $haystackUrl
		 * @return bool
		 */
		public function is_dirs_intersect( $haystackUrl ){
			$haystackUrl = trim( $haystackUrl, '/' );
			return ( count( $this->get_dirs_intersect( $haystackUrl ) ) >= PathsFactory::get( $haystackUrl )->Url()->dirs()->count() );
		}

	}