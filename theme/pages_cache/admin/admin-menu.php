<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 22/11/2018
	 * Time: 12:12
	 */
	
	use hiweb\components\Context;
	use theme\includes\admin;
	use theme\pages_cache;
	use theme\pages_cache\options;


	if( is_admin() ){
		admin::fontawesome();
	}

	add_admin_menu_page( 'hiweb-theme-pages-cache', '<i class="fas fa-car-battery"></i> Pages cache', 'options-general.php' )->function_page( function(){
		include_once dirname(__DIR__) . '/templates/admin-menu-page.php';
	} )->use_default_form( false );


	////
	add_action( 'admin_bar_menu', function( $wp_admin_bar ){
		/** @var WP_Admin_Bar $wp_admin_bar */
		if( context::is_frontend_page() && options::is_enable() ){
			if( pages_cache::get_current_page()->get_cache()->is_actual()){
				$args = [
					'id' => 'hiweb-theme-pagescache-update',
					'title' => '<span style="font-size: 1.2em">♺</span> Обновить кэш страницы',
					'href' => get_path()->url()->get(),
					'meta' => [ 'class' => 'my-toolbar-page' ]
				];
			}else{
				$args = [
					'id' => 'hiweb-theme-pagescache-update',
					'title' => '<span style="font-size: 1.2em">♺</span> Создать кэш страницы',
					'href' => get_path()->url()->get(),
					'meta' => [ 'class' => 'my-toolbar-page' ]
				];
			}
			$wp_admin_bar->add_node( $args );
		}
	}, 999 );