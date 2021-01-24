<?php

use hiweb\components\ComponentsManager\Component;


if ( !function_exists('register_hiweb_component')) {

    /**
     * @param string      $initCallable
     * @param null|string $label
     * @param null|string $description
     * @param bool        $default_enabled
     * @return Component
     */
    function register_hiweb_component(string $initCallable, $label = null, $description = null, $default_enabled = false): Component {
        $component = \hiweb\components\ComponentsManager\ComponentsManager::register_component($initCallable);
        if (is_string($label)) $component->set_label($label);
        if (is_string($description)) $component->set_description($description);
        if ($default_enabled) $component->set_default_enable($default_enabled);
        return $component;
    }
}