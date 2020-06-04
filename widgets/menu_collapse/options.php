<?php

	\theme\includes\admin::fontawesome();

	add_admin_menu_page( \theme\widgets\menu_collapse::$options_handle, '<i class="fas fa-list-alt"></i> Коллапс-меню', 'themes.php' );

	add_field_separator( 'Добавьте, если еще не сделали это, в разделе <a href="' . get_admin_url( null, 'widgets.php' ) . '" target="_blank">"Внешний вид -> Виджеты"</a> виджет, под названием "Навигация по категориям hiWeb" в одну из позиций сайдбара. Так же укажите в данном виджете меню.' )->location()->ADMIN_MENUS( \theme\widgets\menu_collapse::$options_handle );

	add_field_fontawesome('icon-expand')->VALUE('fas fa-plus-circle')->get_parent_field()->label('Иконка "раскрыть"')->location(true);
	add_field_fontawesome('icon-collapse')->VALUE('fas fa-minus-circle')->get_parent_field()->label('Иконка "свернуть"')->location(true);
