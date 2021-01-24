<?php

namespace hiweb\components\Fields\FieldsFactory_Admin;


use hiweb\components\Fields\FieldsFactory;
use hiweb\components\Fields\FieldsFactory_Admin;
use hiweb\components\Structures\StructuresFactory;
use hiweb\core\ArrayObject\ArrayObject;
use hiweb\core\Paths\PathsFactory;
use hiweb\core\Strings;
use WP_Post;
use WP_Screen;


/**
 * Class FieldsFactory_Admin_PostType
 * @package hiweb\components\Fields\FieldsFactory_Admin
 * @version 1.1
 */
class FieldsFactory_Admin_PostType {

    /**
     * @param array $append_post_type_query
     * @param null  $force_wp_post
     * @return array|array[]|null
     * @version 1.1
     */
    static private function get_current_query($append_post_type_query = [], $force_wp_post = null): ?array {
        if ( !function_exists('get_current_screen')) return [];
        ///
        $WP_Post = null;
        if ($force_wp_post instanceof WP_Post) {
            $WP_Post = $force_wp_post;
        } else {
            $WP_Post = get_post(PathsFactory::request('post'));
        }
        if ( !$WP_Post instanceof WP_Post) return null;
        ///
        $post_id = intval($WP_Post->ID);
        $query = [
            'post_type' => [
                'ID' => $post_id,
                'post_type' => $WP_Post->post_type,
                'post_name' => $WP_Post->post_name,
                'post_status' => $WP_Post->post_status,
                'comment_status' => $WP_Post->comment_status,
                'post_parent' => $WP_Post->post_parent,
                'has_taxonomy' => $WP_Post->has_taxonomy,
                'front_page' => $post_id == StructuresFactory::get_front_page_id(),
                'home_page' => $post_id == StructuresFactory::get_blog_id(),
                'template' => get_page_template_slug(intval(PathsFactory::request('post'))),
            ],
        ];
        ///WooCommerce
        if (function_exists('WC') && $post_id > 0) {
            $query['post_type']['woocommerce_shop_page'] = $post_id == get_option('woocommerce_shop_page_id');
            $query['post_type']['woocommerce_cart_page'] = $post_id == get_option('woocommerce_cart_page_id');
            $query['post_type']['woocommerce_checkout_page'] = $post_id == get_option('woocommerce_checkout_page_id');
            $query['post_type']['woocommerce_pay_page'] = $post_id == get_option('woocommerce_pay_page_id');
            $query['post_type']['woocommerce_thanks_page'] = $post_id == get_option('woocommerce_thanks_page_id');
            $query['post_type']['woocommerce_myaccount_page'] = $post_id == get_option('woocommerce_myaccount_page_id');
            $query['post_type']['woocommerce_edit_address_page'] = $post_id == get_option('woocommerce_edit_address_page_id');
            $query['post_type']['woocommerce_view_order_page'] = $post_id == get_option('woocommerce_view_order_page_id');
            $query['post_type']['woocommerce_terms_page'] = $post_id == get_option('woocommerce_terms_page_id');
        } else {
            $query['post_type']['woocommerce_shop_page'] = false;
            $query['post_type']['woocommerce_cart_page'] = false;
            $query['post_type']['woocommerce_checkout_page'] = false;
            $query['post_type']['woocommerce_pay_page'] = false;
            $query['post_type']['woocommerce_thanks_page'] = false;
            $query['post_type']['woocommerce_myaccount_page'] = false;
            $query['post_type']['woocommerce_edit_address_page'] = false;
            $query['post_type']['woocommerce_view_order_page'] = false;
            $query['post_type']['woocommerce_terms_page'] = false;
        }
        if (is_array($append_post_type_query)) $query['post_type'] = array_merge($query['post_type'], $append_post_type_query);
        return $query;
    }


    /**
     * @param WP_Screen $current_screen
     */
    static function _disable_gutenberg_editor($current_screen) {
        if ($current_screen instanceof WP_Screen && intval(PathsFactory::request('post')) > 0 && PathsFactory::request('action') == 'edit') {
            if ($current_screen->base == 'post') {
                $disable_gutenberg = false;
                $disable_editor = false;
                foreach (FieldsFactory::get_field_by_query(self::get_current_query()) as $field) {
                    if ($field->options()->location()->posts()->disable_gutenberg()) {
                        $disable_gutenberg = true;
                    }
                    if ($field->options()->location()->posts()->disable_editor()) {
                        $disable_editor = true;
                    }
                }
                if ($disable_gutenberg) {
                    add_filter('use_block_editor_for_post_type', '__return_false', 10);
                }
                if ($disable_editor) {
                    ?>
                    <style>
                        #postdivrich { display: none }
                    </style>
                    <?php
                }
            }
        }
    }


    static function _edit_form_top() {
        echo FieldsFactory_Admin::get_ajax_form_html(self::get_current_query([ 'position' => 'edit_form_top' ]), [ 'name_before' => 'hiweb-' ]);
    }


    static function _edit_form_before_permalink() {
        echo FieldsFactory_Admin::get_ajax_form_html(self::get_current_query([ 'position' => 'edit_form_before_permalink' ]), [ 'name_before' => 'hiweb-' ]);
    }


    static function _edit_form_after_title() {
        echo FieldsFactory_Admin::get_ajax_form_html(self::get_current_query([ 'position' => 'edit_form_after_title' ]), [ 'name_before' => 'hiweb-' ]);
    }


    static function _edit_form_after_editor() {
        echo FieldsFactory_Admin::get_ajax_form_html(self::get_current_query([ 'position' => 'edit_form_after_editor' ]), [ 'name_before' => 'hiweb-' ]);
    }


    static function _submitpost_box() {
        echo FieldsFactory_Admin::get_ajax_form_html(self::get_current_query([ 'position' => 'submitpost_box' ]), [ 'name_before' => 'hiweb-' ]);
    }


    static function _edit_form_advanced() {
        echo FieldsFactory_Admin::get_ajax_form_html(self::get_current_query([ 'position' => 'edit_form_advanced' ]), [ 'name_before' => 'hiweb-' ]);
    }


    static function _edit_page_form() {
        echo FieldsFactory_Admin::get_ajax_form_html(self::get_current_query([ 'position' => 'edit_page_form' ]), [ 'name_before' => 'hiweb-' ]);
    }


    static function _dbx_post_sidebar() {
        echo FieldsFactory_Admin::get_ajax_form_html(self::get_current_query([ 'position' => 'dbx_post_sidebar' ]), [ 'name_before' => 'hiweb-' ]);
    }


    /**
     * POST META BOXES
     * @version 1.1
     */
    static function _add_meta_boxes() {
        $query = self::get_current_query([ 'position' => '', 'metabox' => [] ]);
        $query_by_box = [];
        $fields = FieldsFactory::get_field_by_query($query);
        if ( !is_array($fields) || count($fields) == 0) return;
        $first_field_location = reset($fields)->options()->location()->posts();
        foreach ($fields as $Field) {
            $box_title = $Field->options()->location()->posts()->metaBox()->title();
            $query_by_box[$box_title] = $query;
            $query_by_box[$box_title]['post_type']['metabox'] = $Field->options()->location()->posts()->metaBox()->_get_optionsCollect();
        }
        foreach ($query_by_box as $title => $query) {
            $box_id = 'hiweb-metabox-' . Strings::sanitize_id($title);
            add_meta_box($box_id, $title, function() {
                $locationQuery = func_get_arg(1)['args'][0];
                echo FieldsFactory_Admin::get_ajax_form_html($locationQuery, [ 'name_before' => 'hiweb-' ]);
            }, $query['post_type']['post_type'], $query['post_type']['metabox']['context'], $query['post_type']['metabox']['priority'], [ $query ]);
        }
    }


    /**
     * @param int     $post_ID
     * @param WP_Post $post
     * @param bool    $update
     * @version 1.1
     */
    static function _save_post($post_ID, $post, $update) {
        if ( !$update || !array_key_exists('hiweb-core-field-form-nonce', $_POST) || !wp_verify_nonce($_POST['hiweb-core-field-form-nonce'], 'hiweb-core-field-form-save')) return;
        foreach (FieldsFactory::get_field_by_query(self::get_current_query([], $post)) as $Field) {
            $field_name = 'hiweb-' . $Field->get_id();
            if ($Field->get_allow_save_field(array_key_exists($field_name, $_POST) ? $_POST[$field_name] : null)) {
                if (array_key_exists($field_name, $_POST)) {
                    update_post_meta($post_ID, $Field->id(), $Field->get_sanitize_admin_value($_POST[$field_name], true));
                } else {
                    update_post_meta($post_ID, $Field->id(), $Field->get_sanitize_admin_value('', true));
                }
            }
        }
    }


    static function manage_posts_columns($posts_columns, $post_type = 'page') {
        $query = [
            'post_type' => [
                'post_type' => $post_type,
                'columns_manager' => [],
            ],
        ];
        $fields = FieldsFactory::get_field_by_query($query);
        if (count($fields) > 0) {
            $posts_columns = ArrayObject::get_instance($posts_columns);
            foreach ($fields as $field_ID => $Field) {
                $ColumnsManager = $Field->options()->location()->posts()->columnsManager();
                $posts_columns->push($ColumnsManager->id(), $ColumnsManager->name());
            }
            $posts_columns = $posts_columns->get();
        }
        return $posts_columns;
    }


    static function manage_posts_custom_column($columns_name, $post_id) {
        if (function_exists('get_current_screen') && strpos($columns_name, 'hiweb-field-') === 0) {
            $field_id = substr($columns_name, strlen('hiweb-field-'));
            $query = [
                'post_type' => [
                    'post_type' => get_current_screen()->post_type,
                ],
            ];
            $Field = FieldsFactory_Admin::get_Field($field_id, $query);
            $callback = $Field->options()->location()->posts()->columnsManager()->callback();
            if ( !is_null($callback) && is_callable($callback)) {
                call_user_func_array($callback, [ $post_id, $Field, $columns_name ]);
            } else {
                echo $Field->get_admin_columns_html(get_post($post_id), $post_id, $columns_name);
            }
        }
    }


    /**
     * @param $sortable_columns
     * @return array
     */
    static function manage_posts_sortable_columns($sortable_columns) {
        $fields = FieldsFactory::get_field_by_query([
            'post_type' => [
                'post_type' => get_current_screen()->post_type,
            ],
        ]);
        foreach ($fields as $Field) {
            if ($Field->options()->location()->posts()->columnsManager()->sortable()) {
                $sortable_columns['hiweb-field-' . $Field->id()] = 'hiweb-field-' . $Field->id();
            }
        }
        return $sortable_columns;
    }

}