<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 09:27
	 */

	add_action( 'wp_enqueue_scripts', '\\hiweb\\css::_add_action_wp_register_script' );
	add_action( 'admin_enqueue_scripts', '\\hiweb\\css::_add_action_wp_register_script' );
	add_action( 'login_enqueue_scripts', '\\hiweb\\css::_add_action_wp_register_script' );
	add_action( 'customize_render_control', '\\hiweb\\css::_the' );
	add_action( 'wp_footer', '\\hiweb\\css::_add_action_wp_register_script' );
	add_action( 'admin_footer', '\\hiweb\\css::_add_action_wp_register_script' );
	add_action( 'shutdown', '\\hiweb\\css::_add_action_wp_register_script' );
	//filters
	add_filter( 'style_loader_tag', '\\hiweb\\css::_add_filter_style_loader_tag', 10, 4 );