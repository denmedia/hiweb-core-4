<?php

if (function_exists('add_action')) {
    add_action('wp_ajax_hiweb_components_fields_type_fontawesome', function() {

        if ( !array_key_exists('search', $_POST)) wp_send_json([ 'success' => false, 'message' => '$_POST[search] not send!' ]);
        $html = '';
        if ($_POST['search'] == '') {
            $data = \hiweb\components\FontAwesome\FontAwesomeFactory::get_icons_data();
            foreach ($data as $name => $icon_raw) {
                if ( !array_key_exists('styles', $icon_raw)) continue;
                if ( !is_array($icon_raw['styles']) || count($icon_raw['styles']) == 0) continue;
                $fist_style = array_shift($icon_raw['styles']);
                if ( !array_key_exists('svg', $icon_raw)) continue;
                if ( !array_key_exists($fist_style, $icon_raw['svg'])) continue;
                if ( !array_key_exists('raw', $icon_raw['svg'][$fist_style])) continue;
                $class = 'fa' . $fist_style[0] . ' fa-' . $name;
                $svg = $icon_raw['svg'][$fist_style]['raw'];
                $html .= '<a href="#" data-result-icon="' . $class . '">' . $svg . '</a>';
            }
        } elseif (\hiweb\components\FontAwesome\is_fontawesome_class_name($_POST['search'])) {
            $Icon = \hiweb\components\FontAwesome\FontAwesomeFactory::get($_POST['search']);
            foreach ($Icon->get_styles() as $style_name) {
                $style = $Icon->get_style($style_name);
                $html .= '<a href="#" data-result-icon="' . $style->get_class() . '">' . $style . '</a>';
            }
        } else {
            $FontAwesome_result = \hiweb\components\FontAwesome\FontAwesomeFactory::get_search_icons($_POST['search']);
            foreach ($FontAwesome_result as $key => $icon) {
                $html .= '<a href="#" data-result-icon="' . $icon->get_class() . '">' . $icon->get_style()->get_raw() . '</a>';
            }
        }

        wp_send_json([ 'success' => false, 'html' => $html, 'result_count' => count($html) ]);
    });
}