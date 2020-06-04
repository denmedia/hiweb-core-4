<?php
	
	add_admin_menu_page( \theme\error_404::$admin_menu_slug, '<i class="fas fa-exclamation-square"></i> Страница 404', \theme\error_404::$admin_menu_parent )->page_title( '<i class="fas fa-exclamation-square"></i> Страница ошибки 404' );
	
	add_field_text( 'title' )->default_value( '404' )->label( 'Титл страницы' )->location()->options( \theme\error_404::$admin_menu_slug );
	
	add_field_content( 'content' )->default_value( '<h2>Упс! Данной страницы не найдено.</h2><h4>Извините...Страницу, которую Вы искали не может быть найдена...</h4>' )->label( 'Содержимое страницы' )->location()->options( \theme\error_404::$admin_menu_slug );