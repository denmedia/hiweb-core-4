<?php
if (function_exists('add_action')) {
    add_action('get_footer', '\hiweb\components\Console\ConsoleFactory::the', 9999999999999);
    add_action('wp_footer', '\hiweb\components\Console\ConsoleFactory::the', 9999999999999);
    add_action('admin_footer', '\hiweb\components\Console\ConsoleFactory::the', 9999999999999);
    add_action('shutdown', '\hiweb\components\Console\ConsoleFactory::the', 9999999999999);
}