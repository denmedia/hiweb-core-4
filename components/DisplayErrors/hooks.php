<?php
if (function_exists('add_action')) {
    add_action('wp_footer', '\hiweb\components\DisplayErrors\DisplayErrors::_echo_footerErrorsHtml');
    add_action('admin_footer', '\hiweb\components\DisplayErrors\DisplayErrors::_echo_footerErrorsHtml');
}