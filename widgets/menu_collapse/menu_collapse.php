<?php

	namespace theme\widgets;


	use theme\includes\frontend;


	class menu_collapse{

		static $defer_include_scripts;
		static $options_handle = 'hiweb-theme-widget-menu-collapse';


		static function init( $defer_include_scripts = true ){
			static $init = false;
			self::$defer_include_scripts = $defer_include_scripts;
			if( !$init ){
				$init = true;
				if( !$defer_include_scripts ){
					frontend::css( __DIR__ . '/style.css' );
					frontend::js( __DIR__ . '/app.js', frontend::jquery() );
				}
				require_once __DIR__ . '/options.php';
				require_once __DIR__ . '/widget.php';
			}
		}

	}