<?php

if (function_exists('add_action')) {

    add_action('init', '\hiweb\components\Taxonomies\TaxonomiesFactory::_register_taxonomy');
}