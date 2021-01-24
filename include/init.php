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
        $include_files = [];
        $init_files = [];
        $shortinit_files = [];
        $dir = opendir($main_dir);
        while(false !== ($sub_dir = readdir($dir))){
            if (preg_match('/(\.|\.\.)/', $sub_dir) > 0) continue;
            $path = $main_dir . DIRECTORY_SEPARATOR . $sub_dir;
            if ( !is_dir($path)) continue;
            $include_array = [ 'functions.php', 'global_functions.php', 'hooks.php', 'init.php', 'shortinit.php' ];
            foreach ($include_array as $fileName) {
                $filePath = $path . DIRECTORY_SEPARATOR . $fileName;
                if (is_file($filePath) && is_readable($filePath)) {
                    if ($fileName == 'init.php') {
                        $init_files[] = $filePath;
                    } elseif ($fileName == 'shortinit.php') {
                        $shortinit_files[] = $filePath;
                    } else {
                        $include_files[] = $filePath;
                        //echo "<p>{$filePath}</p>";
                        require_once $filePath;
                    }
                }
            }
        }
        if ( !defined('SHORTINIT') || SHORTINIT !== true) {
            ///init files include
            foreach ($init_files as $file) {
                require_once $file;
            }
        } else {
            foreach ($shortinit_files as $file) {
                require_once $file;
            }
        }
    }
}

///Include scripts
include_admin_css(HIWEB_DIR_ASSETS . '/css/admin.css');
