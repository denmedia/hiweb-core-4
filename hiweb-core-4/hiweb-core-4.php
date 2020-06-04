<?php
	/*
	Plugin Name: hiWeb Core 4
	Plugin URI: https://github.com/denmedia/hiweb-core-4
	Description: Framework Plugin for WordPress min v5, PHP min v5.6
	Version: 4.0.0.1 develop
	Author: Den Media
	Author URI: http://hiweb.moscow
	*/
	
	if( version_compare( PHP_VERSION, '7.0' ) >= 0 ){
		require_once __DIR__ . '/vendor/autoload.php';
		require_once __DIR__ . '/include/define.php';
		require_once __DIR__ . '/include/init.php';
	}
	else{
		add_action( 'after_setup_theme', function(){
			die( __( 'Your version of PHP must be 7.0 or higher.', 'hiweb-core-4' ) );
		}, 11 );
	}