<?php

use hiweb\components\ComponentsManager\Component;
use hiweb\components\ComponentsManager\ComponentsManager;

add_admin_menu_page('hiweb-components', __('hiWeb Components'), 'plugins.php')->icon_url('fad fa-plug');
$components = get_array(ComponentsManager::get_components());
if ($components->is_empty()) {
    add_field_separator(__('No registered components', 'hiweb-core-4'), sprintf(__('Use code% s to register your component', 'hiweb-core-4'), '<code>\hiweb\components\ComponentsManager::register_component( $initCallback );</code>'))->location()->options('hiweb-components');
} else {
    /** @var Component $component */
    foreach ($components->get() as $component) {
        $checkbox_label = '<div class="component__label">' . htmlentities($component->get_label()) . '</div>';
        if ($component->get_description() !== '') {
            $checkbox_label .= '<div class="component__description">' . htmlentities($component->get_description()) . '</div>';
        }
        add_field_checkbox($component->get_id())->label_checkbox($checkbox_label)->default_value($component->get_default_enable())->location()->options('hiweb-components');
    }
}