<?php

	namespace theme;


	use hiweb\paths;
	use theme\_minify\cache;
	use theme\_minify\template;
	use theme\includes\frontend;


	class minify{

		static private $init = false;
		///options
		static $debug = false;
		static $js_enable = true;
		static $css_enable = true;
		static $critical_css_enable = true;
		static $cache_refresh_time = 86400; //3600 - час, 86400 - сутки, 604800 - неделя

		/** @var string */
		static private $current_template_path;
		/** @var template[] */
		static private $templates = [];


		static function init(){
			if( self::$init ) return;
			self::$init = true;
			frontend::js( __DIR__ . '/includes/frontend.min.js', frontend::jquery() );
			///
			self::$js_enable = get_option( 'hiweb_theme_minify_js_enable', true );
			self::$css_enable = get_option( 'hiweb_theme_minify_css_enable', true );
			self::$critical_css_enable = get_option( 'hiweb_theme_minify_critical_css_enable', true );
			self::$cache_refresh_time = get_option( 'hiweb_theme_minify_cache_refresh_time', 86400 );
			///
			require_once __DIR__ . '/includes/hooks.php';
			require_once __DIR__ . '/includes/cache.php';
			require_once __DIR__ . '/includes/template.php';
			require_once __DIR__ . '/includes/scripts.php';
			require_once __DIR__ . '/includes/js.php';
			require_once __DIR__ . '/includes/css.php';
			require_once __DIR__ . '/includes/html.php';
			require_once __DIR__ . '/includes/critical_html.php';
			require_once __DIR__ . '/includes/critical_css.php';
			require_once __DIR__ . '/admin/admin-menu.php';
		}


		/**
		 * @param string $path
		 */
		static function set_template_path( $path ){
			self::$current_template_path = paths::get( $path )->get_path_relative();
		}


		/**
		 * @return string
		 */
		static function get_template_path(){
			return self::$current_template_path;
		}


		/**
		 * @param $template_id
		 * @return bool|mixed
		 */
		static function get_template_path_by_id( $template_id ){
			return cache::get_template_path_by_id( $template_id );
		}


		/**
		 * @param null $template_path
		 * @return template
		 */
		static function get_template( $template_path = null ){
			if( !is_string( $template_path ) ) $template_path = self::$current_template_path;
			$template_path = paths::get( $template_path )->get_path_relative();
			if( !isset( self::$templates[ $template_path ] ) ){
				self::$templates[ $template_path ] = new template( $template_path );
			}
			return self::$templates[ $template_path ];
		}


	}