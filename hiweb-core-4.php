<?php
	/*
	Plugin Name: hiWeb Core 4
	Plugin URI: http://plugins.hiweb.moscow/core
	Description: Framework Plugin for WordPress min v5, PHP min v5.6
	Version: 4.0.0.0
	Author: Den Media
	Author URI: http://hiweb.moscow
	*/

	if( version_compare( PHP_VERSION, '7.0' ) >= 0 ){
		require_once __DIR__ . '/spl_autoload_register.php';
		require_once __DIR__ . '/define.php';
		require_once __DIR__ . '/init.php';
	} else {
		add_action( 'after_setup_theme', function(){
			die( __( 'Your version of PHP must be 7.0 or higher.', 'hiweb-core-4' ) );
		}, 11 );
	}