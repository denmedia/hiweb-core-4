<?php
	
	namespace hiweb\components\Includes;
	
	
	use hiweb\components\Console\ConsoleFactory;
	use hiweb\components\Context;
	use hiweb\core\Backtrace\Backtrace;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;
	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;
	
	
	class IncludesFactory{
		
		use hidden_methods;
		
		
		static $already_printed = [];
		
		
		static function get_srcs_from_handle( string $handle, $scripts = true, $styles = true ){
			$R = [];
			$_WP_Dependency = false;
			if( $scripts && array_key_exists( $handle, wp_scripts()->registered ) ){
				$_WP_Dependency = wp_scripts()->registered[ $handle ];
			}
			if( $styles && array_key_exists( $handle, wp_styles()->registered ) ){
				$_WP_Dependency = wp_styles()->registered[ $handle ];
			}
			if( $_WP_Dependency instanceof \_WP_Dependency ){
				if( is_array( $_WP_Dependency->deps ) && count( $_WP_Dependency->deps ) > 0 ){
					foreach( $_WP_Dependency->deps as $parent_handle ){
						$R = array_merge( self::get_srcs_from_handle( $parent_handle ), $R );
					}
				}
				$R[ $handle ] = $_WP_Dependency->src;
			}
			return $R;
		}
		
		
		/**
		 * @param null|string $fileNameOrPath
		 * @param string      $extension - file extension. like css/js
		 * @return Path
		 * @version 1.1
		 */
		static private function get_Path_bySearch( $fileNameOrPath = null, $extension = 'css' ){
			return CacheFactory::get( $fileNameOrPath . ':' . $extension, __METHOD__, function(){
				$search_paths = [];
				$fileNameOrPath = func_get_arg( 0 );
				if( array_key_exists( $fileNameOrPath, wp_scripts()->registered ) ){
					$extension = 'js';
					$src = wp_scripts()->registered[ $fileNameOrPath ]->src;
					if( preg_match( '~^\/[\w\-_]+~i', wp_scripts()->registered[ $fileNameOrPath ]->src ) > 0 ){
						$src = ltrim( $src, '/' );
					}
					$Path = PathsFactory::get( $src );
				}
				elseif( array_key_exists( $fileNameOrPath, wp_styles()->registered ) ){
					$extension = 'css';
					$src = wp_styles()->registered[ $fileNameOrPath ]->src;
					if( preg_match( '~^\/[\w\-_]+~i', wp_scripts()->registered[ $fileNameOrPath ]->src ) > 0 ){
						$src = ltrim( $src, '/' );
					}
					$Path = PathsFactory::get( $src );
				}
				else{
					$extension = func_get_arg( 1 );
					$test_file_name = $extension == 'css' ? 'style' : 'script';
					if( is_null( $fileNameOrPath ) ){
						$fileNameOrPath = dirname( Backtrace::Point( 1 )->get_node( 0 )->get_file() ) . '/' . $test_file_name;
					}
					$search_paths = [
						$fileNameOrPath,
						$fileNameOrPath . '.min.' . $extension,
						$fileNameOrPath . '.' . $extension,
						HIWEB_DIR_ASSETS . '/' . $fileNameOrPath,
						HIWEB_DIR_ASSETS . '/' . $fileNameOrPath . '.min.' . $extension,
						HIWEB_DIR_ASSETS . '/' . $fileNameOrPath . '.' . $extension,
						HIWEB_DIR_ASSETS . '/' . $fileNameOrPath . 'style.min.' . $extension,
						HIWEB_DIR_ASSETS . '/' . $fileNameOrPath . 'style.' . $extension,
						get_stylesheet_directory() . '/' . $fileNameOrPath,
						get_stylesheet_directory() . '/' . $fileNameOrPath . '.min.' . $extension,
						get_stylesheet_directory() . '/' . $fileNameOrPath . '.' . $extension,
						get_template_directory() . '/' . $fileNameOrPath,
						get_template_directory() . '/' . $fileNameOrPath . '.min.' . $extension,
						get_template_directory() . '/' . $fileNameOrPath . '.' . $extension,
						PathsFactory::get_root_path() . '/' . $fileNameOrPath,
						PathsFactory::get_root_path() . '/' . $fileNameOrPath . '.min.' . $extension,
						PathsFactory::get_root_path() . '/' . $fileNameOrPath . '.' . $extension,
					];
					$Path = PathsFactory::get_bySearch( $search_paths );
				}
				if( $Path->file()->extension() != $extension ){
					ConsoleFactory::add( 'file [' . $fileNameOrPath . '] not found', 'warn', __CLASS__ . ' - the file is not have ' . $extension . ' extension', $Path->get_path_relative(), true );
				}
				elseif( !$Path->is_local() ){
					return $Path;
				}
				elseif( !$Path->file()->is_file() ){
					ConsoleFactory::add( 'file [' . $fileNameOrPath . '] not file', 'warn', __CLASS__ . ' - ' . $extension . ' file not found', $search_paths, true );
				}
				elseif( !$Path->file()->is_exists() ){
					ConsoleFactory::add( 'file [' . $fileNameOrPath . '] not found', 'warn', __CLASS__ . ' - ' . $extension . ' file not found', $search_paths, true );
				}
				elseif( !$Path->file()->is_readable() ){
					ConsoleFactory::add( 'file [' . $fileNameOrPath . '] not found', 'warn', __CLASS__ . ' - ' . $extension . ' file not readable', $Path->file()->get_relative_path(), true );
				}
				return $Path;
			}, [ $fileNameOrPath, $extension ] )->get_value();
		}
		
		
		/**
		 * @param null $fileNameOrPathOrURL
		 * @return Css
		 */
		static function css( $fileNameOrPathOrURL = null ){
			$Path = self::get_Path_bySearch( $fileNameOrPathOrURL, 'css' );
			return CacheFactory::get( $Path->handle(), __CLASS__ . ':css', function(){
				$Path = func_get_arg( 0 );
				return new Css( $Path );
			}, $Path )();
		}
		
		
		/**
		 * @param null $fileNameOrPathOrURL
		 * @return Js
		 */
		static function js( $fileNameOrPathOrURL = null ){
			$Path = self::get_Path_bySearch( $fileNameOrPathOrURL, 'js' );
			return CacheFactory::get( $Path->handle(), __CLASS__ . ':js', function(){
				$Path = func_get_arg( 0 );
				return new Js( $Path );
			}, $Path )();
		}
		
		
		static protected function _add_action_wp_register_script(){
			foreach( CacheFactory::get_group( __CLASS__ . ':css' ) as $cache_Css ){
				$Css = $cache_Css->get_value();
				if( !$Css instanceof Css ) continue;
				///Stop repeat include
				if( in_array( $Css->Path()->handle(), self::$already_printed ) ) continue;
				///Context check
				if( !( ( Context::is_frontend_page() && $Css->on_frontend() ) || ( Context::is_admin_page() && $Css->on_admin() ) || ( Context::is_login_page() && $Css->on_login() ) ) && !( is_null( $Css->on_frontend() ) && is_null( $Css->on_admin() ) && is_null( $Css->on_login() ) ) ) continue;
				///Footer check
				if( !( did_action( 'wp_footer' ) || did_action( 'admin_footer' ) ) && $Css->to_footer() ) continue;
				///REGISTER STYLE
				wp_register_style( $Css->Path()->handle(), $Css->Path()->url()->get_clear(), $Css->deeps(), $Css->Path()->is_local() ? filemtime( $Css->Path()->file()->get_path() ) : false, $Css->Media()() );
				wp_enqueue_style( $Css->Path()->handle() );
				self::$already_printed[] = $Css->Path()->handle();
			}
			foreach( CacheFactory::get_group( __CLASS__ . ':js' ) as $cache_Js ){
				$Js = $cache_Js->get_value();
				if( !$Js instanceof Js ) continue;
				///Stop repeat include
				if( in_array( $Js->path()->handle(), self::$already_printed ) ) continue;
				///Context check
				if( !( ( Context::is_frontend_page() && $Js->on_frontend() ) || ( Context::is_admin_page() && $Js->on_admin() ) || ( Context::is_login_page() && $Js->on_login() ) ) && !( is_null( $Js->on_frontend() ) && is_null( $Js->on_admin() ) && is_null( $Js->on_login() ) ) ) continue;
				///Footer check
				if( !( did_action( 'wp_footer' ) || did_action( 'admin_footer' ) ) && $Js->to_footer() ) continue;
				///REGISTER SCRIPT
				wp_register_script( $Js->path()->handle(), $Js->path()->url()->get(), $Js->deeps(), $Js->path()->is_local() ? filemtime( $Js->path()->file()->get_path() ) : false, $Js->to_footer() );
				wp_enqueue_script( $Js->path()->handle() );
				self::$already_printed[] = $Js->path()->handle();
			}
		}
		
		
		/**
		 * @param $html
		 * @param $handle
		 * @param $href
		 * @param $media
		 * @return null|string
		 */
		static protected function _add_filter_style_loader_tag( $html = null, $handle = null, $href = null, $media = null ){
			if( CacheFactory::is_exists( $handle, __CLASS__ . ':css' ) ){
				$Css = CacheFactory::get( $handle, __CLASS__ . ':css' )();
				if( $Css instanceof Css ){
					return $Css->get_html();
				}
			}
			return $html;
		}
		
		
		static protected function _add_filter_script_loader_tag( $tag, $handle, $src ){
			if( CacheFactory::is_exists( $handle, __CLASS__ . ':js' ) ){
				$Js = CacheFactory::get( $handle, __CLASS__ . ':js' )();
				if( $Js instanceof Js ){
					return $Js->get_html();
				}
			}
			return $tag;
		}
		
		
//		/**
//		 * @param bool $include_migrate_js
//		 * @return bool
//		 */
//		static function jquery( $include_migrate_js = false ){
//			$R = static::js( HIWEB_THEME_VENDORS_DIR . '/jquery3/jquery-3.3.1.min.js' );
//			if( $include_migrate_js ){
//				static::js( HIWEB_THEME_VENDORS_DIR . '/jquery3/jquery-migrate-1.4.1.min.js' );
//			}
//			return $R->path()->handle();
//		}
		
		
		/**
		 * @return bool|int|string
		 */
		static function jquery_qtip(){
			static::css(HIWEB_DIR_VENDOR.'/jquery.qtip/jquery.qtip.min.css');
			$js = static::js(HIWEB_DIR_VENDOR.'/jquery.qtip/jquery.qtip.min.js');
			//$js->deeps(['jquery-core']);
			return $js->path()->handle();
		}
		
	}