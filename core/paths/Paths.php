<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 01:44
	 */

	namespace hiweb\core\paths;


	use hiweb\core\arrays\Arrays;


	class Paths{

		/** @var Path[] */
		static private $paths;
		static private $root;


		/**
		 * @param string $path
		 * @return Path
		 */
		static function get( $path = '' ){
			$path = str_replace( '\\', '/', (string)$path );
			if( trim( $path, '/' ) == '' ) $path = self::root();
			///
			if( !array_key_exists( $path, self::$paths ) ){
				self::$paths[ $path ] = new Path( $path );
			}
			return self::$paths[ $path ];
		}


		/**
		 * Returns the root folder of the site. This function automatically determines the root folder of the site, based on the search for folders with the wp-config.php file
		 * Возвращает корневую папку сайта. Данная функция автоматически определяет корневую папку сайта, основанная на поиске папок с файлом wp-config.php
		 * @return string
		 * @version 1.5
		 */
		static function root(){
			if( !is_string( self::$root ) ){
				self::$root = '';
				$patch = explode( '/', trim( __DIR__ ) );
				$patches = [];
				$last_path = '';
				foreach( $patch as $dir ){
					if( $dir == '' ){
						continue;
					}
					$last_path .= '/' . $dir;
					$patches[] = $last_path;
				}
				$patches = array_reverse( $patches );
				foreach( $patches as $path ){
					$check_file = $path . '/wp-config.php';
					if( file_exists( $check_file ) && is_file( $check_file ) ){
						self::$root = $path;
						break;
					}
				}
			}
			return self::$root;
		}


		/**
		 * @param int|string $size
		 * @return string
		 */
		static function get_size_formatted( $size ){
			$size = intval( $size );
			if( $size < 1024 ){
				return $size . ' '.__('B');
			} elseif( $size < 1048576 ) {
				return round( $size / 1024, 2 ) . ' ' . __( 'KB' );
			} elseif( $size < 1073741824 ) {
				return round( $size / 1048576, 2 ) . ' ' . __( 'MB' );
			} elseif( $size < 1099511627776 ) {
				return round( $size / 1073741824, 2 ) . ' ' . __( 'GB' );
			} elseif( $size < 1125899906842624 ) {
				return round( $size / 1099511627776, 2 ) . ' ' . __( 'TB' );
			} elseif( $size < 1152921504606846976 ) {
				return round( $size / 1125899906842624, 2 ) . ' ' . __( 'PB' );
			} elseif( $size < 1180591620717411303424 ) {
				return round( $size / 1152921504606846976, 2 ) . ' ' . __( 'EB' );
			} elseif( $size < 1208925819614629174706176 ) {
				return round( $size / 1180591620717411303424, 2 ) . ' ' . __( 'ZB' );
			} else {
				return round( $size / 1208925819614629174706176, 2 ) . ' ' . __( 'YiB' );
			}
		}


		static function file_extension( $file_name ){
			return Arrays::make( pathinfo( $file_name ) )->_( 'extension', '' );
		}

	}