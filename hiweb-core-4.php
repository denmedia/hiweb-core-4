<?php
	/*
	Plugin Name: hiWeb Core 4
	Plugin URI: https://github.com/denmedia/hiweb-core-4
	Description: Framework Plugin for WordPress min v5, PHP min v5.6
	Version: 4.0.0.0 develop
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
	
	//init_adminNotices();
	//init_displayErrors();
	
	add_post_type( 'page' )->show_in_rest( false );
	
	add_field_images('images')->label('Проверка изображения')->Location()->PostType('page');
	
	//	$repeat = add_field_repeat('repeat2');
	//	$repeat->Location()->PostType('page');
	//	$repeat->add_col_field(add_field_info('Тестовое поле для проверка INFO FIELD'))->compact(1);
	//	$repeat->add_col_field(add_field_images( 'images' ))->label('Картинки')->compact(1);
	//	$repeat->add_col_field(add_field_content( 'content' ));