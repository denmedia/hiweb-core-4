<?php

	namespace theme\pages_cache;


	use hiweb\Dump;
	use hiweb\urls;
	use theme\includes\frontend;
	use theme\pages_cache;


	require_once __DIR__ . '/includes/page.php';


	/**
	 * Класс для прямой работы с кэшем из index-hiweb-cache.php
	 * Class direct_index
	 * @package hiweb_theme\pagesCache
	 */
	class pages_cache_direct{

		private static $force_disable_use_cache = false;
		private static $force_disable_make_cache = false;
		private static $force_make_cache = false;
		private static $make_cache_is_process = false;
		private static $debug = false;

		private static $init = false;


		static function init(){
			if( !self::$init ){
				self::$init = true;
				options::init();
				///
				if( isset( $_GET['cache-disable'] ) ){
					self::set_force_disable_use_cache();
					if( $_GET['cache-disable'] == '1' ){
						self::set_force_make_cache();
					}else{
						self::set_force_disable_make_cache();
					}
				}
				///
			}
		}


		/**
		 * @return bool
		 */
		static function is_background_query(){
			return $_SERVER['REMOTE_ADDR'] == gethostbyname( gethostname() );
		}


		/**
		 * @return bool
		 */
		static function is_force_disable_use_cache(){
			return self::$force_disable_use_cache;
		}


		/**
		 * @return bool
		 */
		static function is_force_make_cache(){
			return !self::$force_disable_make_cache && self::$force_make_cache;
		}


		/**
		 * @return bool
		 */
		static function is_force_disable_make_cache(){
			return self::$force_disable_make_cache;
		}


		/**
		 * Принудительно запретить использовать кэш
		 */
		static function set_force_disable_use_cache(){
			self::$force_disable_use_cache = true;
		}


		/**
		 * Принудительно запретить создавать кэш, приоритетно над set_force_make_cache
		 */
		static function set_force_disable_make_cache(){
			self::$force_disable_make_cache = true;
		}


		/**
		 * Принудительно создать кэш
		 */
		static function set_force_make_cache(){
			self::$force_disable_use_cache = true;
			self::$force_make_cache = true;
		}


		/**
		 * @return bool
		 */
		private static function is_cache_use_allow(){
			return ( options::is_enable() && !self::is_force_disable_use_cache() && page::get_page()->get_cache()->is_exists() );
		}


		/**
		 * @return bool
		 */
		private static function is_cache_make_allow(){
			return ( options::is_enable() && !self::is_force_disable_make_cache() && options::is_allow_url( page::get_page()->get_url() ) );
		}


		static function the_start(){
			///
			self::init();
			///
			if( tools::is_frontend_page() && ( self::is_cache_make_allow() || self::is_cache_use_allow() ) ){
				if( self::is_cache_use_allow() && \theme\pages_cache\options::is_allow_url( tools::get_request_uri( true ), true ) ){
					///THE PRINT CACHE CONTENT
					$round_time = round( page::get_page()->get_cache()->get_time_left() / 60, 1 );
					$content = page::get_page()->get_content();
					///
					$seconds_to_cache = 2.592e+6;
					$ts = gmdate( "D, d M Y H:i:s", time() + $seconds_to_cache ) . " GMT";
					header( "Expires: $ts" );
					header( "Pragma: cache" );
					header( "Cache-Control: max-age=$seconds_to_cache" );
					header( 'Content-Type: text/html; charset=utf-8' );
					///
					echo "<!--hiWeb Pages Cache: current cache start, time left: {$round_time}min, page_id=".basename(page::get_page()->get_cache()->get_path())."-->{$content}<!--hiWeb Pages Cache: current cache end-->";
					///DEBUG
					if( self::$debug ){
						echo '<script>console.group("hiWeb Theme Pages Cache"); console.info(' . json_encode( page::get_page()->get_cache()->get_data() ) . '); console.groupEnd();</script>';
					}
					self::append_frontend_js( $content );
					die;
				} elseif( ( self::is_cache_make_allow() || self::is_force_make_cache() ) && \theme\pages_cache\options::is_allow_url( tools::get_request_uri( true ), true ) ) {
					self::$make_cache_is_process = true;
					echo '<!--hiWeb Theme pages Cache: start make cache...-->';
				}
			}
			ob_start();
			///
		}


		static function the_end(){
			///
			$content = ob_get_clean();
			echo $content;
			///
			if( tools::is_frontend_page() && options::is_enable() && options::is_allow_url( tools::get_request_uri( true ), true ) ){

				if( !self::is_background_query() ){
					global $wp_query;
					if( $wp_query instanceof \WP_Query && !$wp_query->is_404() && !$wp_query->is_search() ){

						if( self::$make_cache_is_process ){
							if( is_string( $content ) && strlen( $content ) > 10 ){
								page::get_page()->set_content( $content );
							}
						}

						self::append_frontend_js( $content );
						if( !queue::is_url_exists( page::get_page()->get_url() ) ){
							queue::add_url( page::get_page()->get_url(), 9, 0 );
						}
					}
				}
			} elseif( !pages_cache::is_init() && options::is_enable() ) {
				options::set( 'enable', '', true );
			}
		}


		static private function append_frontend_js( $content ){
			///ADD CACHE JS
			if( preg_match( '/(<script[^>]+src=[\'"][^"]+vendors\/jquery3\/jquery-[\d\.]+(?:min)?\.js[\'"])/mi', $content, $matches ) == 0 ){
				?>
					<!--<script defer src="<?= tools::path_to_url( dirname( dirname( __DIR__ ) ) . '/vendors/jquery3/jquery-3.3.1.min.js' ) ?>"></script>--><?php
			}
			?>
			<script defer src="<?= tools::path_to_url( __DIR__ . '/includes/frontend.min.js' ) ?>"></script><?php
		}


	}