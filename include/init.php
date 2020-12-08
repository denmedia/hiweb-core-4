<?php

///LOAD TEXT DOMAIN
if (function_exists('add_action') && function_exists('load_theme_textdomain')) {
    add_action('after_setup_theme', function() {
        load_theme_textdomain('hiweb-core-4', HIWEB_DIR . '/languages');
    }, 10);
}

///INCLUDE INIT FILE
foreach ([ HIWEB_DIR_CORE, HIWEB_DIR_COMPONENTS ] as $main_dir) {
    if (file_exists($main_dir) && is_dir($main_dir) && is_readable($main_dir)) {
        foreach (scandir($main_dir) as $sub_dir) {
            if (preg_match('/(\.|\.\.)/', $sub_dir) > 0) continue;
            $path = $main_dir . '/' . $sub_dir;
            if ( !is_dir($path)) continue;
            $include_array = [ 'functions.php', 'global_functions.php', 'hooks.php', 'init.php' ];
            foreach ($include_array as $fileName) {
                $filePath = $path . '/' . $fileName;
                if (is_file($filePath) && is_readable($filePath)) {
                    require_once $filePath;
                }
            }
        }
    }
}