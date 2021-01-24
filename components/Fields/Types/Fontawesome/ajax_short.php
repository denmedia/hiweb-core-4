<?php

define('SHORTINIT', true);
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/hiweb-core-4.php';
header('Content-Type: application/json');

if ( !array_key_exists('query', $_POST)) exit(json_encode([ 'success' => false, 'message' => 'Error: $_POST[query] not exists.' ]));

$post_query = stripslashes($_POST['query']);
if ($post_query == '') {
    exit(json_encode([ 'success' => false, 'message' => '$_POST[query] is empty (((. Send `::all` or `::style::duo` or search query like `plus`' ]));
} else {
    $icon_htmls = [];
    if ($post_query === '::all') {
        $icons = \hiweb\components\FontAwesome\FontAwesomeFactory::get_icons_data();
        foreach ($icons as $name => $icon_raw) {
            if ( !array_key_exists('styles', $icon_raw)) continue;
            if ( !is_array($icon_raw['styles']) || count($icon_raw['styles']) == 0) continue;
            $fist_style = array_shift($icon_raw['styles']);
            if ( !array_key_exists('svg', $icon_raw)) continue;
            if ( !array_key_exists($fist_style, $icon_raw['svg'])) continue;
            if ( !array_key_exists('raw', $icon_raw['svg'][$fist_style])) continue;
            $class = 'fa' . $fist_style[0] . ' fa-' . $name;
            $svg = $icon_raw['svg'][$fist_style]['raw'];
            $icon_htmls[$class] = $svg;
        }
    } elseif (preg_match('/^::style::/i', $post_query)) {
        $fontAwesome_Icon = \hiweb\components\FontAwesome\FontAwesomeFactory::get(str_replace('::style::', '', $post_query));
        if ( !$fontAwesome_Icon->is_exists()) {
            exit([ 'success' => false, 'message' => __('Icon not exists', 'hiweb-core-4') ]);
        } else {
            foreach ($fontAwesome_Icon->get_styles() as $styleName) {
                $iconStyle = $fontAwesome_Icon->get_style($styleName);
                $icon_htmls[$iconStyle->get_class()] = $iconStyle->get_raw();
            }
        }
    } else {
        $icons = \hiweb\components\FontAwesome\FontAwesomeFactory::get_search_icons(\hiweb\components\FontAwesome\fontawesome_filter_icon_name($post_query));
        foreach ($icons as $fontAwesome_Icon) {
            $icon_htmls[$fontAwesome_Icon->get_class()] = $fontAwesome_Icon->get_style()->get_raw();
        }
    }
    exit(json_encode([ 'success' => true, 'query' => $post_query, 'icons' => $icon_htmls, 'count' => count($icon_htmls) ]));
}