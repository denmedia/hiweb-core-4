<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 03/12/2018
	 * Time: 22:10
	 */

	namespace hiweb\core\urls;


	/**
	 * Класс-менеджер URL адресов
	 * Class urls
	 * @package hiweb
	 */
	class Urls{

		/** @var Url[] */
		private static $urls = [];
		/** @var string */
		private static $current_url = [];
		static $use_noscheme_urls = true;


		/**
		 * Возвращает текущий адрес URL
		 * @param bool $trimSlashes
		 * @return string
		 * @version 1.1
		 */
		static function get_current_url( $trimSlashes = true ){
			$key = $trimSlashes ? 'trimSlashes:true' : 'trimSlashes:false';
			if( !isset( self::$current_url[ $key ] ) ){
				self::$current_url[ $key ] = '';
				$https = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;
				self::$current_url[ $key ] = rtrim( 'http' . ( $https ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'], '/' ) . ( $trimSlashes ? rtrim( $_SERVER['REQUEST_URI'], '/\\' ) : $_SERVER['REQUEST_URI'] );
			}
			return self::$current_url[ $key ];
		}


		static function set_current_url( $url ){
			self::$current_url['trimSlashes:false'] = $url;
		}


		/**
		 * @param null $url
		 * @return Url
		 */
		static function get( $url = null ){
			if( is_null( $url ) || (string)$url == '' ) $url = self::get_current_url();
			if( !array_key_exists( $url, self::$urls ) ){
				self::$urls[ $url ] = new Url( $url );
			}
			return self::$urls[ $url ];
		}


		/**
		 * Test string to url
		 * @param string $test_url_string
		 * @return bool
		 */
		static function is_url( $test_url_string ){
			return is_string( $test_url_string ) && ( strpos( $test_url_string, '//' ) === 0 || filter_var( $test_url_string, FILTER_VALIDATE_URL ) );
		}


		/**
		 * Возвращает корневой URL
		 * @param null|bool $use_noscheme
		 * @return string
		 * @version 1.0
		 */
		static function root( $use_noscheme = null ){
			return self::get()->root( $use_noscheme );
		}


		/**
		 * Возвращает запрошенный GET или POST параметр
		 * @param       $key
		 * @param mixed $default
		 * @return mixed
		 */
		static function request( $key, $default = null ){
			$R = $default;
			if( array_key_exists( $key, $_GET ) ){
				$R = $_GET[ $key ];
			}
			if( array_key_exists( $key, $_POST ) ){
				$R = is_string( $_POST[ $key ] ) ? stripslashes( $_POST[ $key ] ) : $_POST[ $key ];
			}

			return $R;
		}

	}