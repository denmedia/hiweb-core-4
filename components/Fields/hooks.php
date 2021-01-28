<?php

use hiweb\components\Fields\Field;
use hiweb\components\Fields\Field_Options\Field_Options_Location_AdminMenu;
use hiweb\components\Fields\FieldsFactory;
use hiweb\components\Fields\FieldsFactory_FrontEnd;


if (function_exists('add_action')) {
    //ADMIN HOOKS
    //FORM LOAD
    add_action('wp_ajax_hiweb-components-form', '\hiweb\components\Fields\FieldsFactory_Admin::get_ajax_form_hock');
    //Post Type
    add_action('edit_form_top', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_edit_form_top');
    add_action('edit_form_before_permalink', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_edit_form_before_permalink');
    add_action('edit_form_after_title', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_edit_form_after_title');
    add_action('edit_form_after_editor', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_edit_form_after_editor');
    add_action('submitpost_box', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_submitpost_box');
    add_action('submitpage_box', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_submitpost_box');
    add_action('edit_form_advanced', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_edit_form_advanced');
    add_action('edit_page_form', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_edit_page_form');
    add_action('dbx_post_sidebar', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_dbx_post_sidebar');
    //Post type Meta Box
    add_action('add_meta_boxes', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_add_meta_boxes', 8, 2);
    //Post Disable Gutenberg
    add_action('current_screen', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_disable_gutenberg_editor');
    ///Post Save
    add_action('save_post', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::_save_post', 10, 3);

    //BACKEND HOOKS
    ///Posts List Columns
    add_action('manage_pages_custom_column', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::manage_posts_custom_column', 10, 2);
    add_action('manage_posts_custom_column', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::manage_posts_custom_column', 10, 2);
    add_filter('manage_pages_columns', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::manage_posts_columns', 10, 1);
    add_filter('manage_posts_columns', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::manage_posts_columns', 10, 2);
    //Sort Columns
    add_action('admin_init', function() {
        foreach (get_post_types() as $post_type) {
            add_filter('manage_edit-' . $post_type . '_sortable_columns', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_PostType::manage_posts_sortable_columns', 10, 1);
        }
    });
    //	////////
    ///TAXONOMIES BACKEND
    add_action('init', function() {
        if (function_exists('get_taxonomies') && is_array(get_taxonomies())) foreach (get_taxonomies() as $taxonomy_name) {
            //add
            add_action($taxonomy_name . '_add_form_fields', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_Taxonomy::taxonomy_add_form_fields');
            //edit
            add_action($taxonomy_name . '_edit_form', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_Taxonomy::taxonomy_edit_form', 10, 2);
        }
    }, 100);
    ///TAXONOMY SAVE
    add_action('created_term', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_Taxonomy::taxonomy_edited_term', 10, 3);
    add_action('edit_term', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_Taxonomy::taxonomy_edited_term', 10, 3);

    ///NAV MENUS
    //* @since 5.4.0
    //* @param int      $item_id Menu item ID.
    //* @param WP_Post  $item    Menu item data object.
    //* @param int      $depth   Depth of menu item. Used for padding.
    //* @param stdClass $args    An object of menu item arguments.
    //* @param int      $id      Nav menu ID.
    ///do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args, $id );
    add_action('wp_nav_menu_item_custom_fields', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_NavMenu::wp_nav_menu_item_custom_fields', 10, 5);

    ///NAV MENU UPDATE
    add_action('wp_update_nav_menu_item', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_NavMenu::wp_update_nav_menu_item', 10, 3);

    /// USERS SETTINGS
    /// USER ADD
    //	add_action( 'user_new_form', 'hiweb\\fields\\locations\\admin::user_new_form' );
    /// USER EDIT
    //	add_action( 'admin_color_scheme_picker', 'hiweb\\fields\\locations\\admin::admin_color_scheme_picker' );
    //	add_action( 'personal_options', 'hiweb\\fields\\locations\\admin::personal_options' );
    //	add_action( 'profile_personal_options', 'hiweb\\fields\\locations\\admin::profile_personal_options' );
    //	add_action( 'show_user_profile', 'hiweb\\fields\\locations\\admin::edit_user_profile' );
    //	add_action( 'edit_user_profile', 'hiweb\\fields\\locations\\admin::edit_user_profile' );
    /// USERS SAVE
    //	add_action( 'user_register', 'hiweb\\fields\\locations\\admin::edit_user_profile_update' );
    //	add_action( 'personal_options_update', 'hiweb\\fields\\locations\\admin::edit_user_profile_update' );
    //	add_action( 'edit_user_profile_update', 'hiweb\\fields\\locations\\admin::edit_user_profile_update' );
    //	///OPTIONS FIELDS
    //	add_action( 'admin_init', [ $this, 'options_page_add_fields' ], 999999 );
    //	///ADMIN MENU FIELDS
    //	add_action( 'current_screen', [ $this, 'admin_menu_fields' ], 999999 );

    /// THEME SETTINGS
    //add_action( 'customize_register', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_Customize::customize_register' );

    ///COMMENTS
    //	add_action( 'add_meta_boxes_comment', 'hiweb\\fields\\locations\\admin::add_meta_boxes_comment' );
    //	add_action( 'comment_edit_redirect', 'hiweb\\fields\\locations\\admin::comment_edit_redirect', 10, 2 );

    ///DEFAULT OPTIONSPAGES
    add_action('current_screen', function() {
        if (get_current_screen()->id !== '' && array_key_exists(get_current_screen()->id, Field_Options_Location_AdminMenu::$default_options_pages)) {
            add_settings_section('hiweb-form', null, function() {
                $slug = Field_Options_Location_AdminMenu::$default_options_pages[get_current_screen()->id];
                echo \hiweb\components\Fields\FieldsFactory_Admin::get_ajax_form_html([ 'options' => $slug ], [ 'name_before' => \hiweb\components\Fields\FieldsFactory_Admin::_get_prepend_name_by_options($slug) . '-' ]);
            }, Field_Options_Location_AdminMenu::$default_options_pages[get_current_screen()->id]);
        }
    });

    add_action('check_admin_referer', '\hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_Options_Permalink::_update');
}