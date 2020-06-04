<?php

	namespace theme\pages_cache;


	use hiweb\paths;


	class setup{

		private static $template_index_name = 'index-hiweb-cache.php';
		private static $template_htaccess_name = 'htaccess-template';


		/**
		 * @return string
		 */
		private function get_index_template_path(){
			$tempalte_path = dirname( __DIR__ ) . '/templates/' . self::$template_index_name;
			if( !file_exists( $tempalte_path ) || !is_readable( $tempalte_path ) || !is_file( $tempalte_path ) ) return false;
			return $tempalte_path;
		}


		/**
		 *
		 */
		static function init(){
			//Replace
			$htaccess = paths::get( '/.htaccess' );
			$htaccess_template = paths::get( dirname( __DIR__ ) . '/templates/' . self::$template_htaccess_name );
			$B = true;
			///HTACCESS INJECT
			if( !$htaccess->is_exists() && $htaccess_template->is_writable() ){
				$B = $htaccess->FILE()->make_file( str_replace( '{index-file}', self::$template_index_name, $htaccess_template->get_content() ) );
				if( $B ){
					if( function_exists( 'console_info' ) ){
						console_info( __METHOD__ . ' - файл .htaccess создан!' );
					}
				} elseif( function_exists( 'console_warn' ) ) {
					console_warn( __METHOD__ . ' - не удалось создать файл .htaccess, возможно права на запись отсутствуют' );
				}
			} else {
				$htaccess_content = $htaccess->get_content();
				if( preg_match( '/(?<marker>#hiweb-theme-pages-cache-inject (?>start|end))/im', $htaccess_content ) < 1 ){
					$B = $htaccess->set_content( str_replace( '{index-file}', self::$template_index_name, $htaccess_template->get_content() ), - 1 );
					if( $B ){
						if( function_exists( 'console_info' ) ){
							console_info( __METHOD__ . ' - файл .htaccess изменен!' );
						}
					} elseif( function_exists( 'console_warn' ) ) {
						console_warn( __METHOD__ . ' - не удалось изменить .htaccess, возможно права на запись отсутствуют' );
					}
				}
			}
			///INDEX ROUTE MAKE
			if( $B ){
				$B = false;
				$index_file = paths::get( self::$template_index_name );
				if( !$index_file->is_exists() ){
					$index_template = paths::get( dirname( __DIR__ ) . '/templates/' . self::$template_index_name );
					if( $index_template->get_content( '' ) != '' ){
						$B = $index_file->FILE()->make_file( strtr( $index_template->get_content( '' ), [
							'{hiweb-theme-pages-cache-direct}' => paths::get( dirname( __DIR__ ) )->get_path_relative() . '/pages_cache_direct.php'
						] ) );
					}
					///
					if( $B ){
						if( function_exists( 'console_info' ) ){
							console_info( __METHOD__ . ' - файл ' . self::$template_index_name . ' создан!' );
						}
					} elseif( function_exists( 'console_warn' ) ) {
						console_warn( __METHOD__ . ' - не удалось создать файл [' . self::$template_index_name . '], возможно права на запись отсутствуют' );
					}
				}
			}
			///

		}


	}