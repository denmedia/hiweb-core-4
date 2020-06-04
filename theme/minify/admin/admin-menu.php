<?php

	use theme\includes\admin;

	if( is_admin() ){
		admin::fontawesome();
	}

	add_admin_menu_page( 'hiweb-theme-minify', '<i class="fas fa-forklift"></i> Minify CSS, JS', 'options-general.php' )->function_page( function(){
		include_once dirname(__DIR__) . '/templates/admin-menu-page.php';
	} )->use_default_form( false );