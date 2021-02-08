<?php

use hiweb\components\Breadcrumb\BreadcrumbFactory;


add_admin_menu_page(BreadcrumbFactory::$admin_options_slug, __('Breadcrumb', 'hiweb-core-4'), 'themes.php')->icon_url('far fa-shoe-prints');

///
add_field_tab(__('Home crumb', 'hiweb-core-4'))->location()->options(BreadcrumbFactory::$admin_options_slug);
add_field_checkbox('home-enable')->label_checkbox(__('Enable', 'hiweb-core-4'))->default_value(true)->location(true);
add_field_checkbox('home-icon-enable')->label_checkbox(__('Show icon', 'hiweb-core-4'))->default_value(true)->location(true);
add_field_fontawesome('home-icon')->label(__('Home crumb icon', 'hiweb-core-4'))->default_value('fas fa-home')->location(true);
add_field_checkbox('home-label-enable')->label_checkbox(__('Show label', 'hiweb-core-4'))->default_value(true)->location(true);
add_field_checkbox('home-label-enable-mobile')->label_checkbox(__('Show label on mobile devices', 'hiweb-core-4'))->default_value(true)->location(true);
add_field_text('home-label')->placeholder(get_bloginfo('name'))->label(__('Home label', 'hiweb-core-4'))->description(__('Leave the field blank, in this case the site name will be taken', 'hiweb-core-4'))->location(true);
add_field_checkbox('home-href-enable')->label_checkbox(__('Enable home link', 'hiweb-core-4'))->default_value(true)->location(true);
add_field_text('home-href')->placeholder(get_home_url())->label(__('URL for home crumb', 'hiweb-core-4'))->description(__('Leave the field empty, in this case a link to the main page will be automatically set', 'hiweb-core-4'))->location(true);

///
add_field_tab(__('Current page crumb', 'hiweb-core-4'))->location(true);
add_field_checkbox('current-enable')->label_checkbox(__('Enable current crumb'))->default_value(true)->location(true);
add_field_checkbox('current-enable-mobile')->label_checkbox(__('Show crumb on mobile devices'))->default_value(false)->location(true);
add_field_checkbox('current-enable-href')->label_checkbox(__('Enable url on current crumb item'))->location(true);

///
add_field_tab(__('Separators', 'hiweb-core-4'))->location(true);
add_field_checkbox('sep-enable')->label_checkbox(__('Enable separators'))->default_value(true)->location(true);
add_field_checkbox('sep-enable-last')->label_checkbox(__('Close the list of crumbs with a separator at the end', 'hiweb-core-4'))->location(true);
add_field_fontawesome('sep-icon')->default_value('far fa-angle-right')->label('Separator icon')->location(true);
add_field_checkbox('sep-force-text')->label_checkbox('Use a text separator instead of an icon')->default_value(false)->location(true);
add_field_text('sep-text')->default_value('/')->label('Text separator')->location(true);

///
add_field_tab(__('Other', 'hiweb-core-4'), __('Other crumbs options', 'hiweb-core-4'))->location(true);
add_field_checkbox('hide-mobile')->label_checkbox('Hide breadcrumb on mobile devices')->location(true);
add_field_text('limit-chain')->default_value(2)->label(__('Limiting the elements of the crumb chain', 'hiweb-core-4'))->location(true);
//$priority = add_field_repeat('hide-items');
//$priority->label(__('Hide the following items from the list of crumbs', 'hiweb-core-4'))->location(true);
/////
//$post_types = [];
//foreach (get_post_types([], OBJECT) as $post_type) {
//    $post_types[$post_type->name] = $post_type->label;
//}
//$priority->add_col_flex_field('post_type', add_field_select('post_type')->options($post_types))->flex()->label('Hide Post Type link');
/////
//$taxonomies = [];
//foreach (get_taxonomies() as $taxonomy) {
//    $taxonomyObject = get_taxonomy($taxonomy);
//    $taxonomies[$taxonomy] = $taxonomyObject->label;
//}
//$priority->add_col_flex_field('taxonomy', add_field_select('taxonomy')->options($taxonomies))->flex()->label('Hide Taxonomy link');
///
