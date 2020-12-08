<?php
if (function_exists('add_action')) {
    add_action('wp_ajax_hiweb_components_fields_type_content_input', '\hiweb\components\Fields\Types\Content\Field_Content::_ajax_get_admin_html');
}

