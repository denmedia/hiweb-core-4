<?php
if (function_exists('add_action')) {
    add_action('wp_enqueue_scripts', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script');
    add_action('admin_enqueue_scripts', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script');
    add_action('login_enqueue_scripts', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script');
    //add_action( 'customize_render_control', '\\hiweb\\css::_the' );
    add_action('wp_footer', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script');
    add_action('admin_footer', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script');
    //add_action( 'admin_print_footer_scripts', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script' );
    add_action('shutdown', '\hiweb\components\Includes\IncludesFactory::_add_action_wp_register_script');
}

if (function_exists('add_filter')) {
    //filter html script
    add_filter('style_loader_tag', '\hiweb\components\Includes\IncludesFactory::_add_filter_style_loader_tag', 10, 4);
    add_filter('script_loader_tag', '\hiweb\components\Includes\IncludesFactory::_add_filter_script_loader_tag', 10, 3);
}