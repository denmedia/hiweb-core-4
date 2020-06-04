<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 22/11/2018
	 * Time: 18:35
	 */

	namespace theme\pages_cache;


	use hiweb\urls;
	use theme\pages_cache;


	class tools{

		static private $request_uri;
		static private $current_url;


		static function sanitize_id( $string, $limit = 99, $useRegister = false, $ifEmpty_generateRandomKey = true, $additionSymbolsArr = [] ){
			$symbolsAllowArr = [
				'а' => 'a',
				'б' => 'b',
				'в' => 'v',
				'г' => 'g',
				'д' => 'd',
				'е' => 'e',
				'ё' => 'e',
				'ж' => 'zh',
				'з' => 'z',
				'и' => 'i',
				'й' => 'y',
				'к' => 'k',
				'л' => 'l',
				'м' => 'm',
				'н' => 'n',
				'о' => 'o',
				'п' => 'p',
				'р' => 'r',
				'с' => 's',
				'т' => 't',
				'у' => 'u',
				'ф' => 'f',
				'х' => 'h',
				'ц' => 'c',
				'ч' => 'ch',
				'ш' => 'sh',
				'щ' => 'sh',
				'ъ' => '',
				'ы' => 'i',
				'ь' => '',
				'э' => 'e',
				'ю' => 'yu',
				'я' => 'ya',

				'А' => 'a',
				'Б' => 'b',
				'В' => 'v',
				'Г' => 'g',
				'Д' => 'd',
				'Е' => 'e',
				'Ё' => 'e',
				'Ж' => 'zh',
				'З' => 'z',
				'И' => 'i',
				'Й' => 'y',
				'К' => 'k',
				'Л' => 'l',
				'М' => 'm',
				'Н' => 'n',
				'О' => 'o',
				'П' => 'p',
				'Р' => 'r',
				'С' => 's',
				'Т' => 't',
				'У' => 'u',
				'Ф' => 'f',
				'Х' => 'h',
				'Ц' => 'c',
				'Ч' => 'ch',
				'Ш' => 'sh',
				'Щ' => 'sh',
				'Ъ' => '',
				'Ы' => 'i',
				'Ь' => '',
				'Э' => 'e',
				'Ю' => 'yu',
				'Я' => 'ya',

				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',

				'a' => 'a',
				'b' => 'b',
				'c' => 'c',
				'd' => 'd',
				'e' => 'e',
				'f' => 'f',
				'g' => 'g',
				'h' => 'h',
				'i' => 'i',
				'j' => 'j',
				'k' => 'k',
				'l' => 'l',
				'm' => 'm',
				'n' => 'n',
				'o' => 'o',
				'p' => 'p',
				'q' => 'q',
				'r' => 'r',
				's' => 's',
				't' => 't',
				'u' => 'u',
				'v' => 'v',
				'w' => 'w',
				'x' => 'x',
				'y' => 'y',
				'z' => 'z',
				' ' => '-',
				'_' => '_',
				'-' => '-',
				'(' => '-',
				')' => '-',
				'&' => '-',
				'~' => '-',
				'[' => '-',
				']' => '-',
				'%20' => '-',
				'+' => '-',
				'=' => '-',
				',' => '-',
				'.' => '-'
			];
			///
			if( !is_array( $additionSymbolsArr ) || count( $additionSymbolsArr ) == 0 ){
				$additionSymbolsArr = [];
			} else {
				$symbolsAllowArr = array_merge( $symbolsAllowArr, $additionSymbolsArr );
			}
			///
			if( !is_string( $string ) && !is_int( $string ) ){
				return $ifEmpty_generateRandomKey ? '/' : '';
			}
			$R = '';
			if( is_int( $string ) ){
				return strlen( $string ) > $limit ? substr( $string . '', 0, $limit ) : $string;
			} else {
				for( $list_n = 0; $list_n < strlen( $string ) and $list_n < $limit; $list_n ++ ){
					$symStr = mb_substr( $string, $list_n, 1 ) . '';
					$symStrLow = mb_strtolower( $symStr );
					if( in_array( ord( $symStr ), [ 208, 209 ] ) ){
						//$symStr = (string)substr( $in_name, $list_n, 2 );
						//$symStrLow = (string)mb_strtolower( $symStr, 'UTF-8' );
						//$list_n ++;
					} //Если киррилица, брать 2 символа
					///
					$convertStr = '_';
					if( isset( $symbolsAllowArr[ $symStr ] ) ){
						$convertStr = $symbolsAllowArr[ $symStr ];
					} else if( !$useRegister && isset( $symbolsAllowArr[ $symStrLow ] ) ){
						$convertStr = $symbolsAllowArr[ $symStrLow ];
					} else if( $useRegister && isset( $symbolsAllowArr[ $symStrLow ] ) ){
						$convertStr = strtoupper( $symbolsAllowArr[ $symStrLow ] );
					}
					///
					$R .= $convertStr;
				}
			}
			////
			return rtrim( strtr( $R, [ '___' => '-', '__' => '-' ] ), '-_ ' );
		}


		static function is_frontend_page(){
			return ( preg_match( '/^\/index(-hiweb-cache)?\.php(\/.*)?$/i', $_SERVER['PHP_SELF'] ) > 0 && !self::is_rest_api() && !self::is_ajax() );
		}


		/**
		 * @return bool
		 */
		static function is_ajax(){
			return ( ( defined( 'DOING_AJAX' ) && DOING_AJAX == 1 ) || ( defined( 'WC_DOING_AJAX' ) && WC_DOING_AJAX == 1 ) );
		}


		/**
		 * @return bool
		 */
		static function is_rest_api(){
			$explode = explode( '/', trim( self::get_request_uri( false ), '/' ) );
			return reset( $explode ) == 'wp-json';
		}


		/**
		 * Return TRUE, if context is CRON (request url domain.com/wp-cron.php)
		 * @return bool
		 */
		static function is_cron(){
			return defined( 'DOING_CRON' ) && DOING_CRON;
		}


		/**
		 * Возвращает корневую папку сайта. Данная функция автоматически определяет корневую папку сайта, отталкиваясь на поиске папок с файлом index.php
		 * @return string
		 * @version 1.5
		 */
		static function base_dir(){
			static $base_dir = false;
			if( $base_dir === false ){
				$base_dir = '';
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
						$base_dir = $path;
						break;
					}
				}
			}

			return $base_dir;
		}


		/**
		 * @param $path
		 * @return mixed
		 */
		static function path_to_url( $path ){
			return str_replace( self::base_dir(), self::get_base_url(), $path );
		}


		/**
		 * Возвращает относительный путь до файла / запроса URL
		 * @param      $url
		 * @param bool $include_params
		 * @return bool
		 */
		static function sanitize_url( $url, $include_params = true ){
			///filter url - relative / unique
			preg_match( '~^(?>(?<scheme>(?>https?:\/\/|\/\/))(?<domain>[^\/]+))?(?<uri>[^\?\n]+)(?<params>\?.*)?~im', $url, $matches );
			if( !isset( $matches['uri'] ) ) return false;
			$R = trim( $matches['uri'], '/' );
			if($R == '') $R = '/';
			///remove params
			if( $include_params && isset( $matches['params'] ) ){
				$R .= $matches['params'];
			}
			return $R == '' ? '/' : ltrim( $R . '/' );
		}


		/**
		 * @param bool $include_params
		 * @return mixed
		 */
		static function get_request_uri( $include_params = false ){
			$include_params_key = $include_params ? 'include_params' : 'no_params';
			if( !is_string( self::$request_uri[ $include_params_key ] ) ){
				self::$request_uri[ $include_params_key ] = self::sanitize_url( $_SERVER['REQUEST_URI'], $include_params );
			}
			return self::$request_uri[ $include_params_key ];
		}


		/**
		 * @param bool $trimSlashes
		 * @return string
		 */
		static function get_base_url( $trimSlashes = true ){
			$https = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;
			return rtrim( 'http' . ( $https ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'], '/' ) . ( $trimSlashes ? '' : '/' );
		}


		/**
		 * Возвращает текущий адрес URL
		 * @param bool $trimSlashes
		 * @return string
		 * @version 1.0.2
		 */
		static function get_current_url( $trimSlashes = true ){
			if( !is_string( self::$current_url ) ){
				$https = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;
				self::$current_url = rtrim( 'http' . ( $https ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'], '/' ) . ( $trimSlashes ? rtrim( $_SERVER['REQUEST_URI'], '/\\' ) : $_SERVER['REQUEST_URI'] );
			}
			return self::$current_url;
		}


		/**
		 * @param     $wp_post_or_term
		 * @param int $max_priority
		 * @return array[url] => priority
		 */
		static function get_relative_urls_by_post_or_term( $wp_post_or_term, $max_priority = 10, $exclude_ursl = [] ){
			if( !is_array( $exclude_ursl ) ) $exclude_ursl = [ $exclude_ursl ];
			$R = [];
			if( $wp_post_or_term instanceof \WP_Post ){
				$post_type_archive_link = get_post_type_archive_link( $wp_post_or_term->post_type );
				if( trim( $post_type_archive_link ) != '' ) $R[ get_post_type_archive_link( $wp_post_or_term->post_type ) ] = floor( $max_priority * .2 );
				$R['/'] = floor( $max_priority * .4 );
				if( intval( get_option( 'page_for_posts' ) ) != 0 ) $R[ get_permalink( get_option( 'page_for_posts' ) ) ] = floor( $max_priority * .4 );
				foreach( get_object_taxonomies( $wp_post_or_term ) as $taxonomy ){
					$terms = get_the_terms( $wp_post_or_term, $taxonomy );
					if( is_array( $terms ) ) foreach( $terms as $wp_term ){
						page::get_page( get_term_link( $wp_term ) )->get_cache()->do_flush();
						$R[ tools::sanitize_url( get_term_link( $wp_term ) ) ] = floor( $max_priority * .5 );
					}
				}
			} elseif( $wp_post_or_term instanceof \WP_Term ) {
				foreach(
					get_posts( [
						'post_type' => 'any',
						'tax_query' => [
							[
								'taxonomy' => $wp_post_or_term->taxonomy,
								'field' => 'term_id',
								'terms' => $wp_post_or_term->term_id
							]
						],
						'posts_per_page' => 20
					] ) as $wp_post
				){
					$R[ tools::sanitize_url( get_permalink( $wp_post ) ) ] = floor( $max_priority * .5 );
				}
			}
			///
			foreach( $exclude_ursl as $url ){
				if( isset( $R[ $url ] ) ) unset( $R[ $url ] );
			}
			///
			return $R;
		}


		/**
		 * Return true if browser is mobile
		 * @return bool
		 */
		static function is_mobile(){
			if( isset( $_GET['cache-mobile'] ) ) return true;
			///
			if( empty( $_SERVER['HTTP_USER_AGENT'] ) ){
				$is_mobile = false;
			} elseif( strpos( $_SERVER['HTTP_USER_AGENT'], 'Mobile' ) !== false // many mobile devices (all iPhone, iPad, etc.)
			          || strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' ) !== false || strpos( $_SERVER['HTTP_USER_AGENT'], 'Silk/' ) !== false || strpos( $_SERVER['HTTP_USER_AGENT'], 'Kindle' ) !== false || strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' ) !== false || strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false || strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mobi' ) !== false ) {
				$is_mobile = true;
			} else {
				$is_mobile = false;
			}
			return $is_mobile;
		}

	}

