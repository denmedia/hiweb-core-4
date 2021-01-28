<?php

/*
Plugin Name: hiWeb Core 4
Plugin URI: https://github.com/denmedia/hiweb-core-4
Description: Framework Plugin for WordPress min v5.4, PHP min v7
Version: 4.3.3.0 develop
Author: Den Media
Author URI: http://hiweb.moscow
*/

if (version_compare(PHP_VERSION, '7.2') >= 0) {
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/include/define.php';
    require_once __DIR__ . '/include/init.php';
} else {
    add_action('after_setup_theme', function() {
        die(__('Your version of PHP must be 7.2 or higher.', 'hiweb-core-4'));
    }, 11);
}