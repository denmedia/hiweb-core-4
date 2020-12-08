<?php

	namespace hiweb\core\Paths;


	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\hidden_methods;

    /**
     * Класс для работы с URL
     * Class Path_Url
     * @package hiweb\core\Paths
     * @version 1.3
     */
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
         * Return current URL
         * @return string
         */
		public function __toString(){
		    return $this->get();
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
			return $this->Path()->file();
		}


		/**
		 * Do prepare and base parse URL
		 */
		private function prepare(){
			if( !is_array( $this->prepare_data ) ){
				$this->prepare_data = [];
				if( strpos( $this->url, PathsFactory::get_root_path() ) === 0 ) $this->url = str_replace( PathsFactory::get_root_path(), '', $this->url );
				$pattern = '/((?<schema>[\w]+?):\/\/|\/\/)?(?<domain>[\w\d\-\_]{2,}\.[\w\d\-\_]{1,}(?>\.[\w\d\-\_]+)?(?>\.[\w\d\-\_]+)?)?(?<dirs>[^\?]*)(?<params>.*)/i';
				preg_match( $pattern, $this->url, $this->prepare_data );
				///SCHEMA
				if( array_key_exists( 'schema', $this->prepare_data ) && $this->prepare_data['schema'] != '' ){
					$this->schema = $this->prepare_data['schema'];
				}
				else{
					$this->schema = ( ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? 'https' : 'http';
				}
				///DOMAIN
				if( array_key_exists( 'domain', $this->prepare_data ) && $this->prepare_data['domain'] != '' ){
					$this->domain = $this->prepare_data['domain'];
				}
				else{
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
			return $this->schema;
		}


		/**
		 * @param null|bool $use_noscheme
		 * @return string
		 * @version 1.1
		 */
		public function base( $use_noscheme = null ){
			$key = json_encode( $use_noscheme );
			if( !isset( $this->base[ $key ] ) || !is_string( $this->base[ $key ] ) ){
				if( !is_bool( $use_noscheme ) ) $use_noscheme = PathsFactory::$use_universal_schema_urls;
				$this->base[ $key ] = ( $use_noscheme ? '//' : $this->schema() . '://' ) . $this->domain();
			}
			return $this->base[ $key ];
		}


		/**
		 * @return bool
		 */
		public function is_ssl(){
			return $this->schema() === 'https';
		}


		/**
		 * @return string
		 */
		public function domain(){
			return $this->domain;
		}


		/**
		 * @param null $return_universalScheme
		 * @return string
		 */
		public function get( $return_universalScheme = null ){
			if( !is_string( $this->prepare_url ) ){
				$this->prepare_url = $this->base( $return_universalScheme ) . ( $this->dirs()->is_empty() ? '' : '/' . $this->dirs()->join( '/' ) ) . ( $this->params()->is_empty() ? '' : '?' . $this->params()->get_params_url() );
			}
			return $this->get_clear().( $this->params()->is_empty() ? '' : '?'.$this->params()->get_params_url() );
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
         * @version 1.2
		 */
		public function params(){
			if( !$this->params instanceof ArrayObject ){
				$this->params = new ArrayObject();
				if( !is_string( $this->params_str ) ) $this->params_str = '';
				if( strlen( $this->params_str ) > 0 ){
				    $parse_str = urldecode( $this->params_str );
				    parse_str ($parse_str, $result);
				    if(is_array($result)) {
				        foreach ($result as $key => $val) {
				            $test_val = json_decode( $val );
				            if(json_last_error() == JSON_ERROR_NONE) {
                                $this->params->set_value($key, $test_val);
                            } else {
                                $this->params->set_value($key, $val);
                            }
                        }
                    } else {
                        $this->params->set($result);
                    }

				}
			}
			return $this->params;
		}


		/**
		 * @param array|ArrayObject $params
		 * @return $this
         * @version 1.1
		 */
		public function set_params( $params ){
		    if($params instanceof ArrayObject) $params = $params->get();
			if( is_array( $params ) ) foreach( $params as $key => $val ){
				if( is_null( $val ) ){
					$this->params()->unset_key( $key );
				}
				else{
					$this->params()->push( $key, $val );
				}
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
			$this->dirs()->rows()->each( function( $index, $dir ){
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
			return ( count( $this->get_dirs_intersect( $haystackUrl ) ) >= PathsFactory::get( $haystackUrl )->url()->dirs()->count() );
		}

	}