<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 06/12/2018
	 * Time: 09:29
	 */
	
	use hiweb\core\Paths\PathsFactory;
	
	
	/**
	 * @var \theme\pwa self
	 */

	theme\includes\admin::fontawesome();

//	add_action( 'current_screen', function(){
		//if( get_current_screen()->base == 'dashboard' && !PathsFactory::get()->is_ssl() ) hiweb\admin::NOTICE( 'Внимание! На сайте не установлен SSL. Это требуется для того, чтобы браузер работал в режиме PWA.' )->CLASS_()->error();
//	} );

	$admin_menu = add_admin_menu_page( \theme\pwa::$admin_menu_slug, '<i class="fal fa-mobile-android"></i> Progress Web App', \theme\pwa::$admin_menu_parent );
	$admin_menu->page_title( '<i class="fal fa-mobile-android"></i> Установки Progressive Web Application' );

	add_field_tab( '<i class="fal fa-cog"></i> Основные настройки' )->location()->options( 'hiweb-theme-pwa' );

	add_field_image( 'icon' )->label( 'FAVICON - Иконка приложения в формате PNG' )->description( 'Реккомендуемый размер иконки не менее 192пикс, иначе посетителю не будет задан вопрос об установке ссылки на рабочий стол' )->location( true );

	add_field_script( 'script-head' )->label( 'Скрипт для всех страниц сайта, будет расположен внутри тега <code>head</code> (до тега <code>body</code>)' )->location( true );
	add_field_script( 'script' )->label( 'Скрипт для всех страниц сайта, будет расположен в футере сайта' )->location( true );

	add_field_text( 'viewport' )->label('Meta Viewport')->description('<a href="https://developer.mozilla.org/ru/docs/Mozilla/Mobile/Viewport_meta_tag" target="_blank">Using the viewport meta tag to control layout on mobile browsers - Mozilla | MDN</a>')->default_value( 'width=device-width, initial-scale=1' )->location( true );

	add_field_tab('Progressive Web App')->location(true);
	
	add_field_image( 'icon-splash' )->label( 'Иконка на сплэш-экран, в формате PNG' )->description( 'Это изображение будет показано на экране зщагрузки приложения, реккомендуемый (минимальный) размер 512пикс. Если не устанавливать иконку, будет автоматически заимствовано изображен е основной иконки.' )->location( true );
	add_field_text( 'name' )->placeholder( get_bloginfo( 'name' ) )->label( 'Наименовние приложения' )->location( true );
	add_field_text( 'short_name' )->placeholder( get_bloginfo( 'name' ) )->label( 'Короткое наименовние приложения' )->location( true );
	add_field_text( 'description' )->placeholder( get_bloginfo( 'description' ) )->label( 'Описание приложения' )->location( true );
	add_field_text( 'related_applications-play' )->label( 'Ссылка в Google Play для андроид приложения' )->location( true );
	add_field_text( 'related_applications-itunes' )->label( 'Ссылка в iTunes для iOS приложения' )->location( true );
	add_field_select( 'orientation' )->placeholder('--выберите пункт--')->options( [ 'any' => 'Любое положение', 'natural' => 'Натуральное', 'portrait' => 'Портретный', 'landscape' => 'Горизонтальный', 'portrait-primary' => 'Портретный (приоритетно)', 'portrait-secondary' => 'Портретный (второстепенно)', 'landscape-primary' => 'Горизонтальный (приоритетно)', 'landscape-secondary' => 'Горизонтально (второстепенно)' ] )->label( 'Привязанная ориентация приложения' )->location( true );
	add_field_select( 'display' )->placeholder('--выберите пункт--')->options( [ 'fullscreen' => 'Полноэкранный режим', 'minimul-ui' => 'Минимальный', 'standalone' => 'Отдельное окно', 'browser' => 'Стандартный браузерный' ] )->label( 'Стиль отображения' )->location( true );
	add_field_color( 'theme_color' )->default_value( '#ffffff' )->label( 'Цвет темы' )->description( 'Данный цвет используется в статус-баре, если приложение работает не полноэкранном режиме, а так же отображается в фоне заголовка, под иконкой приложения в основной окне прилоржения' )->location( true );
	add_field_color( 'background_color' )->default_value('#ffffff' )->label( 'Цвет заднего фона' )->description( 'В момент загрузки приложения этот цвет заполняет экран под иконкой' )->location( true );

	///
	add_field_tab( '<i class="fas fa-cog"></i> Service Worker' )->location( true );
	add_field_checkbox( 'service-worker-enable' )->label_checkbox( 'Включить поддержку Service Worker' )->location( true );

	///
	add_field_tab( '<i class="fab fa-safari"></i> Установки для Safari iOS' )->location( true );
	add_field_checkbox( 'apple-mobile-web-app-capable' )->label_checkbox( 'Полноэкранный режим браузера' )->description( 'Опция удаляет адресную строку и кнопки навигации по умолчанию в Safari iOS' )->location( true );
	add_field_select( 'apple-mobile-web-app-status-bar-style' )->placeholder('--выберите пункт--')->options( [ 'default' => 'Стандартный', 'black' => 'Черный статус бар', 'black-translucent' => 'Прозрачный с черным текстом' ] )->default_value('default' )->label( 'Стиль статус бара в Safari iOS' )->description( '' )->location( true );

	///
	add_field_tab( '<i class="fab fa-android"></i> Установка для браузера','Android и Google Chrome (на iOS)' )->location( true );
	add_field_color( 'head-meta-theme-color' )->label( 'Цвет адресной строки в браузерах (для Android 5.0 и выше)' )->location( true );