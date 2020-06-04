<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 25/11/2018
	 * Time: 12:08
	 */

	namespace theme\pages_cache;


	use hiweb\Dump;


	require_once __DIR__ . '/tools.php';


	class options{


		static private $options_file_name = 'hiweb-theme-pages-cache-options.json';
		static private $options = null;
		static private $default_disabled_urls = [
			'/robots.txt',
			'/wp-*/*',
			'/wp-*.php',
			'/*.xls',
			'/*.xml',
			'/*.txt',
			'/?wc-ajax',
			'/?s=*'
		];
		static private $dynamic_selectors = [
			'#wpadminbar'
		];
		/** @var bool Возмоэность отключать создание кжша мануально */
		static private $allow_manual = true;


		static function init(){
			$file = self::get_file_path();
			if( !file_exists( $file ) ){
				file_put_contents( $file, json_encode( [] ) );
			}
		}


		/**
		 * @return string
		 */
		static private function get_file_path(){
			return tools::base_dir() . '/wp-content/' . self::$options_file_name;
		}


		/**
		 * @return array
		 */
		static function get_disabled_urls(){
			$config_arr = ( explode( "\n", self::get( 'disabled_urls' ) ) );
			$R = self::$default_disabled_urls;
			foreach( $config_arr as $line ){
				$line = trim( $line );
				if( $line == '' ) continue;
				if( array_search( $line, $R ) !== false ) $R[] = $line;
			}
			$R = array_unique( $R );
			return $R;
		}


		/**
		 * @return array
		 */
		static function get_dynamic_selectos(){
			$config_arr = explode( "\n", self::get( 'dynamic_selectors' ) );
			$R = self::$dynamic_selectors;
			foreach( $config_arr as $line ){
				$line = trim( $line );
				if( $line == '' ) continue;
				if( array_search( $line, $R ) !== false ) $R[] = $line;
			}
			$R = array_unique( $R );
			return $R;
		}


		/**
		 * @return array
		 */
		static function get_options(){
			if( is_null( self::$options ) ){
				self::$options = [];
				if( file_exists( self::get_file_path() ) ){
					$json_data = json_decode( file_get_contents( self::get_file_path() ), true );
					if( json_last_error() == JSON_ERROR_NONE && is_array( $json_data ) ){
						self::$options = $json_data;
					}
				}
			}
			return self::$options;
		}


		/**
		 * @param      $key
		 * @param null $default_value
		 * @return mixed|null
		 */
		static function get( $key, $default_value = null ){
			$options = self::get_options();
			return array_key_exists( $key, $options ) ? $options[ $key ] : $default_value;
		}


		/**
		 * @param      $key
		 * @param null $value
		 * @param bool $force_update_file - true -> обновить файл опций прямо сейчас, иначе нужно добавлять отдельно строку
		 */
		static function set( $key, $value = null, $force_update_file = false ){
			self::get_options();
			if( is_null( $value ) ) unset( self::$options[ $key ] ); else self::$options[ $key ] = $value;
			if( $force_update_file ) self::update();
		}


		/**
		 * Сохранить (обновить) опции в файле JSON
		 * @return bool|int
		 */
		static function update(){
			return file_put_contents( self::get_file_path(), json_encode( self::$options ) );
		}

		//////


		/**
		 * @return bool
		 */
		static function is_enable(){
			return self::get( 'enable', '' ) == 'on';
		}

		static function is_background_enable(){
			return self::get( 'enable-background', '' ) == 'on';
		}


		/**
		 * Мануально отключить/включать создавать кэш на текущем запросе к сайту
		 * @param bool   $set
		 * @param string $reason_message
		 */
		static function set_allow_manual( $set = false, $reason_message = '' ){
			self::$allow_manual = $set;
			if( !$set && $reason_message != '' ){
				?>
				<script>console.info(<?=json_encode( $reason_message )?>)</script><?php
			}
		}


		/**
		 * @return bool
		 */
		static function is_allow_create_cache(){
			return self::is_enable() && self::$allow_manual;
		}


		static function is_allow_url( $url, $include_params = false ){
			$url = '/' . tools::sanitize_url( $url, $include_params );
			$disallow_urls = options::get_disabled_urls();
			foreach( $disallow_urls as $d_url ){
				$pattern = '~' . strtr( $d_url, [ '/' => '\/', '*' => '.*' ] ) . '~i';
				if( trim( $url, '/' ) == trim( $d_url, '/' ) || preg_match( $pattern, $url ) > 0 ){
					return false;
				}
			}
			return true;
		}


	}