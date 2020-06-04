<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05/12/2018
	 * Time: 23:35
	 */

	namespace theme;


	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\Paths\Path_File;
	use hiweb\core\Paths\PathsFactory;
	use theme\html_layout\tags\head;
	use theme\pwa\favicon;


	class pwa{

		static private $init = false;
		static $admin_menu_slug = 'hiweb-theme-pwa';
		static $admin_menu_parent = 'options-general.php';
		static private $service_worker_filename = 'service-worker.js';
		/** @var Path_File */
		static private $service_worker;
		/** @var ArrayObject */
		static private $service_worker_cach_urls = [ '/' ];


		/**
		 * @version 1.1
		 */
		static function init(){
			///
			if( self::$init ) return;
			self::$init = true;
			///
			self::$service_worker_cach_urls = ArrayObject::get_instance( self::$service_worker_cach_urls );

			require_once __DIR__ . '/options.php';
			require_once __DIR__ . '/rest.php';

			favicon::init();

			//viewport meta tag
			if( get_field( 'viewport', self::$admin_menu_slug ) != '' ){
				head::add_html_addition( "<meta name='viewport' content='" . get_field( 'viewport', self::$admin_menu_slug ) . "'>" );
			}

			//manifest link
			head::add_html_addition( '<link rel="manifest" href="' . get_rest_url( null, 'hiweb-theme/pwa/manifest' ) . '">' );

			//add script to head tag
			if( trim( get_field( 'script-head', self::$admin_menu_slug ) ) != '' ) {
				add_action('wp_head', function(){
					the_field( 'script-head', self::$admin_menu_slug );
				});
			}
			//add script to footer
			if( trim( get_field( 'script', self::$admin_menu_slug ) ) != '' ) add_action( '\theme\html_layout\body::the_after-before', function(){
				the_field( 'script', self::$admin_menu_slug );
			} );

			if( get_field( 'head-meta-theme-color', self::$admin_menu_slug ) != '' ){
				head::add_html_addition( '<meta name="theme-color" content="' . get_field( 'head-meta-theme-color', self::$admin_menu_slug ) . '">' );
			}
			head::add_code( '<meta name="apple-mobile-web-app-capable" content="' . ( get_field( 'apple-mobile-web-app-capable', self::$admin_menu_slug ) ? 'yes' : 'no' ) . '">' );
			head::add_code( '<meta name="apple-mobile-web-app-status-bar-style" content="' . get_field( 'apple-mobile-web-app-status-bar-style', self::$admin_menu_slug ) . '">' );

			if( get_field( 'service-worker-enable', self::$admin_menu_slug ) ){
				self::$service_worker = PathsFactory::get_file( '/' . self::$service_worker_filename );
				$B = false; //TODO-
				//$B = self::make_service_worker();
				if( $B === true || $B === - 1 ){
					//includes::async_script_file( 'pwa' );
				}
			} else {
				//includes::defer_script_file( 'pwa-unreg' );
			}
		}


		/**
		 * @return bool|string
		 */
		static function get_generated_service_worker_content(){
			$template = PathsFactory::get_file( __DIR__ . '/pwa/service-worker-template.js' );
			if( !$template->is_readable() ) return false;
			if( $template->get_content() == '' ) return false;
			$R = strtr( $template->get_content( '' ), [
				'{cache_urls:cache_urls}' => self::$service_worker_cach_urls->Json()->get()
			] );
			return $R;
		}


		/**
		 * Make service worker js file in root, if them not exists...
		 * @return bool|int
		 */
		static function make_service_worker(){
			if( self::$service_worker->is_exists() ) return - 1;
			if( self::$service_worker->is_writable() ) return - 2;
			$new_content = self::get_generated_service_worker_content();
			if( $new_content == '' ) return - 3;
			return self::$service_worker->make_file( $new_content );
		}
		
		
		/**
		 * @param $url
		 * @return ArrayObject|string[]
		 */
		static function add_service_worker_cache_url( $url ){
			self::$service_worker_cach_urls->push( $url );
			return self::$service_worker_cach_urls;
		}

	}