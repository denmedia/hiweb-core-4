<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 22/11/2018
	 * Time: 12:02
	 */

	namespace theme;


	use theme\htaccess\injector;


	class htaccess{

		static private $init = false;
		static $template_files = ['cache_2','mod_deflate','mod_expires','mod_gzip','mod_headers'];
		static $mod_expires_time = '1 year';
		static $mod_expires_time_default = '2 days';


		static function init(){
			if( self::$init ) return;
			self::$init = true;
			///
			require_once __DIR__.'/includes/injector.php';
			injector::setup();
		}


	}