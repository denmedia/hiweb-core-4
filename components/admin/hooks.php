<?php

	//Show Admin menu PAGES
	add_action( 'admin_menu', 'hiweb\\admin\\pages\\pages::_hook_admin_menu' );

	//Show NOTICES
	add_action( 'admin_notices', 'hiweb\\admin\\notices\\notices::_hook_admin_notices' );