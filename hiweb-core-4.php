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
	
	add_admin_menu_page( 'test', 'Тестовая опция','options-general.php' )->submit_button_icon('check');
	
	add_field_separator('Проверка опций тут','Это описание опции. Лишь интерактивные прототипы, превозмогая сложившуюся непростую экономическую ситуацию, превращены в посмешище, хотя само их существование приносит несомненную пользу обществу. Безусловно, высокотехнологичная концепция общественного уклада, а также свежий взгляд на привычные вещи - безусловно открывает новые горизонты для приоритизации разума над эмоциями. В своём стремлении улучшить пользовательский опыт мы упускаем, что реплицированные с зарубежных источников, современные исследования представляют собой не что иное, как квинтэссенцию победы маркетинга над разумом и должны быть разоблачены. Ясность нашей позиции очевидна: постоянный количественный рост и сфера нашей активности позволяет оценить значение инновационных методов управления процессами!')->Location()->Options('test');
	add_field_text('test')->label('Проверка первой опции!')->Location()->Options('test');
	add_field_text('test2')->label('Проверка первой опции 2!')->Location()->Options('test2');
	add_field_color('color')->label('Цвет (выбор цвета, колорпикер)')->description('Выберите цвет через колорпикер выше')->Location()->Options('test');
	
	//	$repeat = add_field_repeat('repeat2');
	//	$repeat->Location()->PostType('page');
	//	$repeat->add_col_field(add_field_info('Тестовое поле для проверка INFO FIELD'))->compact(1);
	//	$repeat->add_col_field(add_field_images( 'images' ))->label('Картинки')->compact(1);
	//	$repeat->add_col_field(add_field_content( 'content' ));