<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-21
	 * Time: 11:18
	 */
	
	theme\seo::$admin_menu_main_page = add_admin_menu_page( theme\seo::$admin_menu_main, '<i class="fab fa-searchengin"></i> SEO', theme\seo::$admin_menu_main_parent );
	
	add_action( 'init', function(){
		$separator = add_field_tab( 'Заголовок архивной страницы' )->location()->options( theme\seo::$admin_menu_main );
		$has_archives = false;
		foreach( get_post_types() as $post_type ){
			/** @var WP_Post_Type $post_type */
			$post_type = get_post_type_object( $post_type );
			if( $post_type->has_archive ){
				$has_archives = true;
				add_field_text( 'archive-title-' . $post_type->name )->placeholder( $post_type->label )->label( 'Заголовок для архивной страницы "' . $post_type->label . '"' )->description( 'Оставьте поле пустым6 в таком случае будет использовано название данного типа записей <code>' . $post_type->labels->name . '</code>' )->location( true );
			}
		}
		if( !$has_archives ) add_field_info( 'таких страниц не обнаружено' )->location( true );
		///
		add_field_tab( 'Мета-теги на страницах' )->location()->ADMIN_MENUS( theme\seo::$admin_menu_main );
		$has_public = false;
		foreach( get_post_types() as $post_type ){
			/** @var WP_Post_Type $post_type */
			$post_type = get_post_type_object( $post_type );
			if( $post_type->public && ( $post_type->_builtin || $post_type->publicly_queryable ) ){
				$has_public = true;
				add_field_checkbox( 'enable-' . $post_type->name )->label_checkbox( 'Включить поддержку настроек для "' . $post_type->label . '"' )->default_value( 'on' )->location( true );
				add_field_checkbox( 'enable-custom-h1-' . $post_type->name )->label_checkbox( 'Включить поддерджку индивидуального H1 для "' . $post_type->labels->name . '"' )->default_value( 'on' )->location( true );
				add_field_checkbox( 'enable-custom-loop-title-' . $post_type->name )->label_checkbox( 'Включить поддерджку индивидуального архивного заголовка "' . $post_type->labels->name . '"' )->default_value( 'on' )->location( true );
			}
		}
		if( !$has_public ) add_field_info( 'таких страниц не обнаружено' )->location( theme\seo::$admin_menu_main );
		///
		
		add_field_separator( 'Мета-теги на страницах авторов' )->location()->ADMIN_MENUS( theme\seo::$admin_menu_main );
		add_field_checkbox( 'authors-enable' )->label_checkbox( 'Включить поддержку SEO страницы автора статей' )->location( true );
		
		add_field_tab( 'Другие общие настройки' )->location()->ADMIN_MENUS( theme\seo::$admin_menu_main );
		add_field_checkbox( 'paged-append-enable' )->label_checkbox( 'Включить добавление номера страницы в тайтле, например "Главная - страница 2 из 2"' )->location( true );
		add_field_text( 'paged-append-template' )->default_value( 'Страница {page} из {pages}' )->label( 'Шаблон титла для страница-пагинаций' )->location( true );
		add_field_select( 'redirect-last-slash-mod' )->options( [ 'default' => '--выберите вариант--', 'none' => 'Редирект 301 на URL без слэша в конце', 'slash' => 'Редирект 301 на URL вместе со слэшем в конце' ] )->label( 'Режим URL\'а и знак "/" (слэша) в конце' )->location( true );
		
		///CANONICAL OPTIONS
		add_field_tab('Ссылки CANONICAL')->location(true);
		add_field_separator( 'Установки ссылок CANONICAL' )->location( true );
		add_field_checkbox( 'canonical-paged-first-link' )->label_checkbox( 'Выводить каноническую ссылку на странице пагинации, ссылка на первую страницу' )->description( 'Пример кода: <code>' . htmlentities( '<link rel=”canonical” href=”http://...” />' ) . '</code>' )->location( true );
		add_field_checkbox( 'canonical-paged-prev-next-links' )->label_checkbox( 'Выводить канонические ссылки на странице пагинации, ссылки на предыдущую и следующую страницы' )->description( 'Пример кода: <code>' . htmlentities( '<link rel=”next” href=”http://...” />' ) . '</code>' )->location( true );
		add_field_checkbox( 'canonical-all-pages' )->label_checkbox( 'Выводить каноническую ссылку на всех страницах не пагинации, ссылка на текущую страницу' )->description( 'Пример кода: <code>' . htmlentities( '<link rel=”canonical” href=”http://...” />' ) . '</code>' )->location( true );
	}, 10 );