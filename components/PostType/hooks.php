<?php
if (function_exists('add_action')) {
    add_action('init', '\hiweb\components\PostType\PostTypeFactory::_register_post_types');
}