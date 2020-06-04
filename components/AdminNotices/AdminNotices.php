<?php

	namespace hiweb\components\AdminNotices;


	class AdminNotices{

		static private $init = false;
		
		static private $notices = [];

		static function init(){
			if(!self::$init) {
				self::$init = true;
				include_admin_css( HIWEB_DIR_VENDOR . '/jquery.noty/noty.css' );
				include_admin_css( HIWEB_DIR_VENDOR . '/jquery.noty/themes/bootstrap-v3.css' );
				include_admin_css( HIWEB_DIR_VENDOR . '/animate-css/animate.min.css' );
				include_admin_css( __DIR__ . '/style.css' );
				$js = include_admin_js( HIWEB_DIR_VENDOR . '/jquery.noty/noty.min.js', [ 'jquery-core' ] );
				$js_2 = include_admin_js( HIWEB_DIR_VENDOR . '/javascript-md5/md5.min.js' );
				include_admin_js( __DIR__ . '/App.min.js', [ $js->path()->handle(), $js_2->path()->handle() ] );
				require_once __DIR__.'/hooks_2.php';
			}
		}


		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}
		
		
		static public function _hook_admin_notices(){
			if( is_array( self::$notices ) ) foreach( self::$notices as $notice ){
				if( $notice instanceof notice ){
					$notice->the();
				}
			}
		}

	}