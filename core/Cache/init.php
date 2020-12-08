<?php

if (function_exists('add_action')) {
    add_action('init', function() {
        ///CLEAR CACHE FILES
        \hiweb\core\Cache\CacheFactory::clear_old_files();
    });
} else {
    \hiweb\core\Cache\CacheFactory::clear_old_files();
}


