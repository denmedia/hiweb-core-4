<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-10
	 * Time: 18:11
	 */

	namespace theme\languages;


	use hiweb\core\Paths\PathsFactory;
	use theme\languages;


	class detect{

		static $sub_domain = '';
		static $doimaun_original = '';
		static $url_prefix = '';
		static $uri_original = '';
		static $browser_lang_accept_id;

		static private $is_sub_domain = false;
		static private $is_uri_prefix = false;
		static private $is_browser = false;

		private static $lang_id;


		static function init(){
			///Check SubDomain
			$domain = PathsFactory::get_url()->domain();
			if( substr_count( $domain, '.' ) > 1 ){
				$explode = explode( '.', $domain );
				if( languages::is_exists( $explode[0] ) ){
					self::$sub_domain = $explode[0];
				}
			}
			///Check SubFolder
			$params = '';
			if( strpos( '?', $_SERVER['REQUEST_URI'] ) !== false ) [ $dirs, $params ] = explode( '?', $_SERVER['REQUEST_URI'] ); else $dirs = $_SERVER['REQUEST_URI'];
			$explode = explode( '/', $dirs );
			self::$uri_original = $_SERVER['REQUEST_URI'];
			if( languages::is_exists( $explode[1] ) ){
				self::$url_prefix = $explode[1];
				unset ( $explode[1] );
			}
			//			else {
			//				self::$url_prefix = languages::get_default_id();
			//			}
			$_SERVER['REQUEST_URI'] = join( '/', $explode ) . ( $params != '' ? '?' . $params : '' );
		}


		/**
		 * Return true, if WP use multisite
		 * @return bool
		 */
		static function is_wp_user_multisite(){
			return defined( 'WP_ALLOW_MULTISITE' ) && defined( 'MULTISITE' ) && WP_ALLOW_MULTISITE && MULTISITE;
		}


		/**
		 * Return true, if language detect by multisite
		 * @return mixed
		 */
		static function is_multisite(){
			return self::is_wp_user_multisite() && get_field( 'multisite', languages::$options_page_slug ) != '';
		}


		/**
		 * @return bool
		 */
		static function is_sub_domain(){
			return self::$is_sub_domain;
		}


		/**
		 * @return bool
		 */
		static function is_url_prefix(){
			return self::$is_uri_prefix;
		}


		/**
		 * @return bool
		 */
		static function is_browser(){
			return self::$is_browser;
		}


		/**
		 * Get detect result
		 */
		static function get_id(){
			if( !is_string( self::$lang_id ) ){
				if( self::is_multisite() ){
					if( rtrim( PathsFactory::root()->get_url(), '/' ) == rtrim( PathsFactory::get_current_url(), '/' ) && (string)self::get_id_by_browser() != '' && self::get_id_by_browser() != get_field( 'default-id', languages::$options_page_slug ) && ( !isset( $_SERVER['HTTP_REFERER'] ) || $_SERVER['HTTP_REFERER'] == '' ) ){
						self::$lang_id = self::get_id_by_browser();
						//wp_redirect( languages::get_language( self::$lang_id )->get_url(), 302 );
					} else {
						self::$lang_id = get_field( 'default-id', languages::$options_page_slug );
					}
				} else {
					///SubDomain
					if( self::$sub_domain != '' && languages::is_exists( self::$sub_domain ) ){
						self::$lang_id = self::$sub_domain;
						self::$is_sub_domain = true;
					} ///URL PREFIX
					elseif( self::$url_prefix != '' && languages::is_exists( self::$url_prefix ) ) {
						self::$lang_id = self::$url_prefix;
						self::$is_uri_prefix = true;
					} ///CHECK BROWSER
					elseif( self::get_id_by_browser() != '' ) {
						self::$lang_id = self::get_id_by_browser();
						self::$is_browser = true;
					} ///DEFAULT
					else {
						self::$lang_id = languages::get_default_id();
					}
				}
			}
			return self::$lang_id;
		}


		/**
		 * @return bool|string
		 */
		static function get_id_by_browser(){
			if( !is_string( self::$browser_lang_accept_id ) ){
				self::$browser_lang_accept_id = '';
				if( array_key_exists( substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 ), languages::get_languages() ) ){
					self::$browser_lang_accept_id = substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
				}
			}

			return self::$browser_lang_accept_id;
		}


		/**
		 * @deprecated
		 * @return bool|string
		 */
		static protected function autodetect_lang_id(){
			$R = '';
			///CHECK SUBDOMAIN
			preg_match( '/^(?<subdomain>[\w\-_]+)\..*/i', $_SERVER['HTTP_HOST'], $domains );
			if( $R == '' && array_key_exists( 'subdomain', $domains ) && array_key_exists( $domains['subdomain'], self::get_languages() ) ){
				$R = $domains['subdomain'];
			}
			///CHECK URL LANG REQUEST
			if( $R == '' ){
				if( preg_match( '/^\/(?<lang_id>[\w\d-_]+)\/?.*/i', $_SERVER['REQUEST_URI'], $matches ) > 0 && isset( $matches['lang_id'] ) ){
					if( self::is_exists( $matches['lang_id'] ) ){
						$R = $matches['lang_id'];
					}
				}
			}
			///CHECK CURRENT POST/PAGE
			if( $R == '' && PathsFactory::get_current_url() != PathsFactory::root()->get_url() && function_exists( 'get_queried_object' ) ){
				if( get_queried_object() instanceof \WP_Post && !is_front_page() ) $R = self::get_post( get_queried_object_id() )->get_lang_id();
				if( get_queried_object() instanceof \WP_Term ) $R = self::get_term( get_queried_object_id() )->get_lang_id();
			}
			///SET FROM SESSIONS
			//			if( session_id() == '' ) session_start();
			//			if( $R == '' && array_key_exists( self::$session_key, $_SESSION ) ){
			//				$test_lang_id = $_SESSION[ self::$session_key ];
			//				if( self::is_exists( $test_lang_id ) ){
			//					$R = $test_lang_id;
			//				}
			//			}

			///SET DEFAULT LANG
			if( $R == '' ){
				$R = self::get_default_id();
			}
			return $R;
		}

	}