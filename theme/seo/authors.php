<?php

	use theme\seo;


	add_action( 'init', function(){
		if( seo::is_author_enable() ){
			add_field_separator( 'SEO настройки страницы автора' )->location()->USERS();
			add_field_text( 'seo-custom-h1' )->label( 'Индивидуальный заголовок H1' )->description( 'Оставьте поле пустым для использования основного заголовка текущей страницы' )->location()->USERS();
			//add_field_text( 'seo-custom-loop-title' )->label( 'Индивидуальный заголовок на архивной странице' )->description( 'Оставьте поле пустым для использования основного заголовка текущей страницы' )->LOCATION()->USERS();
			add_field_text( 'seo-meta-title' )->label( 'Заголовок (title)' )->location()->USERS();
			add_field_textarea( 'seo-meta-description' )->label( 'Описание страницы (description)' )->location()->USERS();
			add_field_text( 'seo-meta-keywords' )->label( 'Ключевые слова (keywords)' )->location()->USERS();
		}
	} );