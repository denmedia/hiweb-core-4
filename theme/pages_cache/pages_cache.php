<?php

	namespace theme;


	use theme\includes\admin;
	use theme\pages_cache\options;
	use theme\pages_cache\page;
	use theme\pages_cache\queue;
	use theme\pages_cache\setup;


	require_once __DIR__ . '/includes/tools.php';
	require_once __DIR__ . '/includes/options.php';
	require_once __DIR__ . '/includes/setup.php';
	require_once __DIR__ . '/includes/page.php';
	require_once __DIR__ . '/includes/queue.php';
	require_once __DIR__ . '/includes/hooks.php';
	require_once __DIR__ . '/admin/admin-menu.php';


	class pages_cache{

		/** @var page */
		static private $current_page;
		static private $init = false;


		static function init(){
			if(self::$init) return;
			self::$init = true;
			options::init();
			setup::init();
			queue::init();
			if( options::is_enable() ){
				admin::js( __DIR__ . '/admin/admin-background-make.min.js', admin::jquery() );
				if( isset( $_GET['cache-mobile'] ) ){
					add_filter( 'wp_is_mobile', '__return_true' );
				}
			}
		}


		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}


		/**
		 * @return page
		 */
		static function get_current_page(){
			if( !self::$current_page instanceof page ){
				self::$current_page = page::get_page();
			}
			return self::$current_page;
		}


		/**
		 * @return bool
		 */
		static function is_enable(){
			return options::is_enable();
		}

	}