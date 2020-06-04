<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/10/2018
	 * Time: 11:06
	 */
	
	use theme\breadcrumbs;
	use theme\includes\includes;
	
	
	if( \hiweb\components\Context::is_admin_page() ){
		includes::fontawesome();
	}
	
	add_admin_menu_page( breadcrumbs::$admin_options_slug, '<i class="far fa-shoe-prints"></i> Хлебные крошки', 'options-general.php' );
	
	///HOME CRUMB
	add_field_tab( 'Настройки домашней крошки')->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_separator( 'Настройки домашней крошки', 'Установки самого первого элемента, ведущего на главную страницу' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'home-enable' )->label_checkbox( 'Показывать в хлебных крошках домашнюю страницу' )->VALUE( 'on' )->get_parent_field()->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_fontawesome( 'home-icon' )->label( 'Иконка для домашней крошки' )->VALUE( 'fas fa-home' )->get_parent_field()->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'home-text-enable' )->label_checkbox( 'Включить в домашней крошке текст для ссылки' )->VALUE( 'on' )->get_parent_field()->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_text( 'home-text' )->placeholder( get_bloginfo( 'name' ) )->label( 'Текст ссылки домашней кношки' )->description( 'Оставьте поле пустым, в таком случае будет взято название сайта' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_text( 'home-href' )->placeholder( get_home_url() )->label( 'Ссылка главной крошки' )->description( 'Оставьте поле пустым, в таком случае будет автоматически установлена ссылка на главную страницу' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	
	///SEPARATE CRUMB
	add_field_tab( 'Установки разделителя крошек' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'separator-enable' )->label_checkbox( 'Использовать иконку разделителя крошек' )->VALUE( 'on' )->get_parent_field()->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'separator-last-enable' )->label_checkbox( 'Показывать иконку в конце крошек (если иконки включены)' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_fontawesome( 'separator-icon' )->VALUE( 'far fa-angle-right' )->get_parent_field()->label( 'иконка разделителя крошек' )->description( 'Оставьте иконку пустой, чтобы не использовать ее' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_text( 'separator-text' )->label( 'Текстовой символ разделителя' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	
	///Nav Menu
	//add_field_tab( 'Структура крошек' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	//	$locations = \hiweb\themes::get()->locations();
	//	$R = [ '' => '--не учитывать--' ];
	//	foreach( $locations as $location_name => $nav_menu_id ){
	//		$R[ $location_name ] = '' . wp_get_nav_menu_name( $location_name ) . ' (локация меню: ' . $location_name . ')';
	//	}
	//add_field_select( 'nav_menu' )->options( $R )->label( 'Учитывать структуру крошек из выбранной навигации' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	
	///Current Page
	add_field_tab( 'Текущая страница' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'current-enable' )->label_checkbox( 'Показывать текущую страницу' )->VALUE( 'on' )->get_parent_field()->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'current-url' )->label_checkbox( 'Использовать ссылку на текущую страницу' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	
	///TAXONOMIES
	add_action( 'wp_loaded', function(){
		add_field_tab( 'Опции для отдельных таксономий' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
		foreach( get_taxonomies( [ 'public' => true ] ) as $taxonomy_name ){
			add_field_separator( '', 'Управление таксономией: <b>' . $taxonomy_name . '</b>' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
			add_field_checkbox( 'taxonomy-' . $taxonomy_name . '-enable' )->label_checkbox( 'Показывать эту таксономию в крошках' )->VALUE( 'on' )->get_parent_field()->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
		}
		if( !get_array( get_post_types( [ 'has_archive' => true ] ) )->is_empty() ){
			add_field_tab( 'Показывать в хлебных крошках архивные страницы' )->location()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
			foreach( get_post_types( [ 'has_archive' => true ] ) as $post_type_name ){
				$wp_post_type = get_post_type_object( $post_type_name );
				if( !$wp_post_type instanceof WP_Post_Type ) continue;
				add_field_checkbox( 'post-type-archive-show-' . $wp_post_type->name )->label_checkbox( "Показывать ссылку на архивную страницу типа записей '<b>{$wp_post_type->label}</b>'" )->VALUE( 'on' )->get_parent_field()->location( true );
			}
		}
	} );