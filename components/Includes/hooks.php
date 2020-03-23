<?php

	add_action( 'wp_enqueue_scripts', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script' );
	add_action( 'admin_enqueue_scripts', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script' );
	add_action( 'login_enqueue_scripts', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script' );
	//add_action( 'customize_render_control', '\\hiweb\\css::_the' );
	add_action( 'wp_footer', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script' );
	add_action( 'admin_footer', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script' );
	add_action( 'shutdown', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script' );
	//filter html script
	add_filter( 'style_loader_tag', '\hiweb\components\Includes\IncludesFactory::_add_filter_style_loader_tag', 10, 4 );