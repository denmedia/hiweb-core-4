<?php

	//Show Admin menu PAGES
	add_action( 'admin_menu', '\hiweb\components\AdminMenu\AdminMenuFactory::_register_admin_menus' );