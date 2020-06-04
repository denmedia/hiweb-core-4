<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 19:29
	 */

	\theme\includes\admin::fontawesome();

	add_admin_menu_page( \theme\scrolltop::$admin_menu_slug, '<i class="fas fa-arrow-to-top"></i> Scroll Top', \theme\scrolltop::$admin_menu_parent );

	add_field_fontawesome( 'icon' )->VALUE( 'far fa-arrow-to-top' )->get_parent_field()->label( 'Иконка на кнопке' )->location()->ADMIN_MENUS( \theme\scrolltop::$admin_menu_slug );

	add_field_image( 'image' )->label( 'Использовать изображение, вместо иконки' )->description( 'Если установить изображение, иконка будет проигнорированна' )->location()->ADMIN_MENUS( \theme\scrolltop::$admin_menu_slug );

	add_field_text( 'text' )->label( 'Подпись для стрелки, например <code>вверх</code>' )->location()->ADMIN_MENUS( \theme\scrolltop::$admin_menu_slug );

	add_field_text( 'scroll-speed' )->label( 'Скорость прокрутки вверх, милисек' )->VALUE( 1000 )->get_parent_field()->location( true );
	add_field_text( 'fade-speed' )->label( 'Скорость появления/исчезновения кнопки, милисек' )->VALUE( 300 )->get_parent_field()->location( true );
	add_field_text( 'scroll-offset' )->label( 'Расстояние скролла сверху, когда нужно показать кнопку, пикс' )->VALUE( 200 )->get_parent_field()->location( true );

	add_field_text( 'target_selector' )->label( 'Селектор целевого объекта, до верха которого будет скроллить кнопка' )->VALUE( 'body' )->get_parent_field()->location( true );