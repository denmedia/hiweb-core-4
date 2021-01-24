<?php

add_action('after_setup_theme', function() {

    ///Options
    require_once __DIR__ . '/options.php';

    ///init components
    foreach (\hiweb\components\ComponentsManager\ComponentsManager::get_components() as $component) {
        if ($component->is_enable()) $component->init();
    }
});