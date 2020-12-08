<?php

namespace hiweb\components\Fields;


use hiweb\components\Console\ConsoleFactory;
use hiweb\components\Context;
use hiweb\components\Fields\Types\Tab\Field_Tab;
use hiweb\components\Includes\IncludesFactory;
use hiweb\core\hidden_methods;
use hiweb\core\Paths\PathsFactory;


/**
 * Class FieldsFactory_Admin provides the work of forms in the backend
 * @package hiweb\components\Fields
 * @version 1.1
 */
class FieldsFactory_Admin {

    use hidden_methods;


    private static $the_form_field;
    private static $the_form_fields_query;
    private static $the_form_field_value;
    private static $the_form_field_name;
    private static $the_form_options;


    /**
     * Return field name(id) form options page and options field register
     * @param Field|string $field_or_id
     * @param null|string  $options_slug
     * @return string
     */
    static public function get_field_input_option_name($field_or_id, $options_slug = null) {
        $field_id = '';
        if ($field_or_id instanceof Field) {
            $field_id = $field_or_id->id();
            if (is_null($options_slug)) {
                $options_slug = $field_or_id->options()->location()->_('options') . '-';
            }
        } elseif (is_string($field_or_id) && $field_or_id != '') {
            $field_id = $field_or_id;
            if (is_string($options_slug)) {
                $options_slug .= '-';
            }
        }
        if (is_null($options_slug)) {
            $options_slug = '';
        }
        return 'hiweb-option-' . $options_slug . $field_id;
    }


    /**
     * @param $field_id
     * @return string
     */
    static function get_columns_field_id($field_id) {
        return 'hiweb-field-' . $field_id;
    }


    /**
     * @param field $field
     * @return string
     */
    static public function get_fieldset_classes(field $field) {
        $classes = [ 'hiweb-fieldset' ];
        //$classes[] = 'hiweb-fieldset-width-' . $field->FORM()->WIDTH()->get();
        $classes[] = 'hiweb-field-' . $field->id();
        $classes[] = 'hiweb-field-' . $field->options()->_('global_id');
        return implode(' ', $classes);
    }


    /**
     * @return Field
     */
    static function get_the_field() {
        return self::$the_form_field;
    }


    /**
     * @return mixed
     */
    static function get_the_field_value() {
        return self::$the_form_field_value;
    }


    /**
     * @return string
     */
    static function get_the_field_name() {
        return self::$the_form_field_name;
    }


    /**
     * Get current admin fields query
     * @return array
     * @deprecated
     */
//    static function get_current_fields_query_by_currentScreen() {
//        $R = [
//
//        ];
//        if (function_exists('get_current_screen')) {
//            if (get_current_screen()->base == 'post') {
//                $R = [
//                    'post_type' => [
//                        'ID' => $_GET['post'],
//                        'post_type' => get_current_screen()->post_type
//                    ]
//                ];
//            } else {
//                console_warn(get_current_screen());
//            }
//        }
//        return $R;
//    }


    /**
     * @param null $location_query
     * @return array
     */
    static function get_current_location_raw_values($location_query = null) {
        $R = [];
        $fields = FieldsFactory::get_field_by_query(is_array($location_query) ? $location_query : self::$the_form_fields_query);
        foreach ($fields as $Field) {
            if (array_key_exists('post_type', $location_query)) {
                if (metadata_exists('post', $location_query['post_type']['ID'], $Field->id())) {
                    $R[$Field->id()] = get_post_meta($location_query['post_type']['ID'], $Field->id(), true);
                }
            } elseif (array_key_exists('nav_menu', $location_query)) {
                if (metadata_exists('post', $location_query['nav_menu']['ID'], $Field->id())) {
                    $R[$Field->id()] = get_post_meta($location_query['nav_menu']['ID'], $Field->id(), true);
                } else {
                    $R[$Field->id()] = null;
                }
            } elseif (array_key_exists('taxonomy', $location_query)) {
                if ($Field->get_allow_save_field()) {
                    $R[$Field->id()] = get_term_meta($location_query['taxonomy']['term_id'], $Field->id(), true);
                } else {
                    $R[$Field->id()] = null;
                }
            } elseif (array_key_exists('comment', $location_query)) {
                if ($Field->get_allow_save_field()) {
                    $R[$Field->id()] = get_comment_meta($location_query['comment']['comment_ID'], $Field->id(), true);
                } else {
                    $R[$Field->id()] = null;
                }
            } elseif (array_key_exists('user', $location_query)) {
                if ($Field->get_allow_save_field()) {
                    $R[$Field->id()] = get_user_meta($location_query['user']['ID'], $Field->id(), true);
                } else {
                    $R[$Field->id()] = null;
                }
            } elseif (array_key_exists('options', $location_query)) {
                if ($Field->get_allow_save_field()) {
                    $R[$Field->id()] = get_option('hiweb-option-' . $location_query['options'] . '-' . $Field->id(), null);
                } else {
                    $R[$Field->id()] = null;
                }
            } else {
                $R[$Field->id()] = null;
            }
        }
        return $R;
    }


    /**
     * @param       $field_query - QUERY LOCATION ARRAY
     * @param array $form_options
     * @return false|string
     */
    static function get_ajax_form_html($field_query, $form_options = []) {
        if ( !is_array($field_query)) return '';
        IncludesFactory::jquery_qtip();
        IncludesFactory::js(HIWEB_DIR_VENDOR . '/jquery.regex-selector/jquery.regex-selector.min.js')->deeps('jquery-core');
        IncludesFactory::js(__DIR__ . '/FieldsAdmin.min.js')->deeps('jquery-core');
        IncludesFactory::css(__DIR__ . '/css/FieldsAdmin.css');
        if (count(FieldsFactory::get_field_by_query($field_query)) == 0) return '<!--HIWEB FIELDS FORM is EMPTY-->';
        //Init fields
        foreach (FieldsFactory::get_field_by_query($field_query) as $Field) {
            $Field->admin_init();
        }
        //Print Fields FORM
        ob_start();
        self::get_wp_nonce_field();
        include __DIR__ . '/FieldsFactory_Admin/templates/form-ajax.php';
        static $footer_printed = false;
        if ( !$footer_printed) {
            $footer_printed = true;
            add_action('admin_print_footer_scripts', function() {
                ?>
                <script>
                    let hiweb_components_fields_form_scripts_done = <?=json_encode(wp_scripts()->done)?>;
                </script><?php
            }, 999999);
        }
        return ob_get_clean();
    }


    /**
     *
     */
    static function get_ajax_form_hock() {
        $fields = [];
        $css = [];
        $js = [];
        $fields_query = json_decode(stripslashes($_POST['field_query']), true);
        $form_options = json_decode(stripslashes($_POST['form_options']), true);
        $debug = 0;
        if (json_last_error() == JSON_ERROR_NONE && is_array($fields_query)) {
            self::$the_form_fields_query = $fields_query;
            self::$the_form_options = $form_options;
            $values = self::get_current_location_raw_values(self::$the_form_fields_query);
            $fields = FieldsFactory::get_field_by_query(self::$the_form_fields_query);
            $debug = 1;
            foreach ($fields as $Field) {
                $debug = 2;
                if ($Field->id() != '') {
                    $field_css = $Field->get_css();
                    $css = array_merge($css, is_array($field_css) ? $field_css : [ $field_css ]);
                    $field_js = $Field->get_js();
                    $js = array_merge($js, is_array($field_js) ? $field_js : [ $field_js ]);
                }
            }
        }
        ///Scripts done
        $scripts_done = $_POST['scripts_done'];
        if (json_last_error() != JSON_ERROR_NONE || !is_array($fields_query)) {
            $scripts_done = [];
        }
        ///
        $css = array_unique($css);
        $js = array_unique($js);
        $css_filtered = [];
        $js_filtered = [];
        $js_extra = [];
        $js_not_included = [];
        foreach ($css as $index => $file) {
            if (preg_match('/^[\w\-_]+$/', $file) > 0) {
                foreach (IncludesFactory::get_srcs_from_handle($file, false, true) as $handle => $src) {
                    $Path = PathsFactory::get($src);
                    $css_filtered[$handle] = $Path->url()->get();
                }
            } else {
                $Path = PathsFactory::get($file);
                $css_filtered[$Path->handle()] = $Path->url()->get();
            }
        }
        foreach ($js as $index => $file) {
            if (preg_match('/^[\w\-_]+$/i', $file) > 0) {
                if (in_array($file, $scripts_done)) {
                    $js_not_included[$file . ':1'] = PathsFactory::get($file)->get_path_relative();
                    continue;
                }
                foreach (IncludesFactory::get_srcs_from_handle($file, true, false) as $handle => $src) {
                    if (in_array($handle, $scripts_done)) {
                        $js_not_included[$file . ':2'] = $file;
                        continue;
                    }
                    if (in_array($handle, $scripts_done)) continue;
                    $Path = PathsFactory::get($src);
                    $js_filtered[$handle] = $Path->url()->get();
                    $js_extra[$handle] = wp_scripts()->registered[$handle]->extra['data'];
                }
            } else {
                $Path = PathsFactory::get($file);
                if ( !in_array($Path->handle(), $scripts_done)) {
                    if ($Path->is_local()) $js_filtered[$Path->handle()] = $Path->url()->get(); else $js_filtered[$Path->handle()] = $Path->get_original_path();
                }
            }
        }
        ///
        $form_html = self::get_form_html($fields, $values, $form_options);
        ob_start();
        ConsoleFactory::the();
        $form_html .= ob_get_clean();
        ///
        wp_send_json([
            'success' => true,
            'query' => self::$the_form_fields_query,
            'form_options' => $form_options,
            'debug' => $debug,
            'values' => $values,
            'scripts_done' => $scripts_done,
            'css' => $css_filtered,
            'js' => $js_filtered,
            'js_extra' => $js_extra,
            'js_not_included' => $js_not_included,
            'max_input_nesting_level' => ini_get('max_input_nesting_level'),
            'max_input_vars' => ini_get('max_input_vars'),
            'max_input_time' => ini_get('max_input_time'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'field_ids' => array_keys($fields),
            'form_html' => $form_html
        ]);
    }


    static function get_wp_nonce_field() {
        static $nonce_printed = false;
        if ( !Context::is_ajax() && !$nonce_printed) {
            $nonce_printed = true;
            wp_nonce_field('hiweb-core-field-form-save', 'hiweb-core-field-form-nonce', false);
        }
    }


    /**
     * @param array $fields_by_order_array
     * @param array $field_values
     * @param array $form_options
     * @return string
     */
    static function get_form_section_fields_html($fields_by_order_array = [], $field_values = [], $form_options = []) {
        $fields_array = [];
        ksort($fields_by_order_array);
        foreach ($fields_by_order_array as $order => $fields) {
            if ( !is_array($fields)) continue;
            $fields_array = array_merge($fields_array, $fields);
        }
        $fields_html = [];
        foreach ($fields_array as $Field) {
            if ( !$Field instanceof Field) {
                ConsoleFactory::add('its not Field instance', 'warn', __METHOD__, [ $Field ], true);
                continue;
            }
            ///SCRIPTS
            $js_scripts = $Field->get_js();
            if (is_string($js_scripts)) $js_scripts = [ $js_scripts ];
            if (is_array($js_scripts)) foreach ($js_scripts as $js_script) {
                IncludesFactory::js($js_script);
            }
            ///STYLES
            $css_scripts = $Field->get_css();
            if (is_string($css_scripts)) $css_scripts = [ $css_scripts ];
            if (is_array($css_scripts)) foreach ($css_scripts as $css_script) {
                IncludesFactory::css($css_script);
            }
            ///
            self::$the_form_field = $Field;
            $value = null;
            if (is_array($field_values)) {
                if (array_key_exists($Field->id(), $field_values) && !is_null($field_values[$Field->id()])) $value = $field_values[$Field->id()]; elseif ( !is_null($Field->options()->default_value())) $value = $Field->options()->default_value();
            }
            if (is_null($value) && !is_null($Field->options()->default_value())) {
                $value = $Field->options()->default_value();
            }
            ///set current field value
            self::$the_form_field_value = $Field->get_sanitize_admin_value($value);
            ///set current field name
            self::$the_form_field_name = (isset($form_options['name_before']) ? $form_options['name_before'] : '') . $Field->get_ID() . (isset($form_options['name_after']) ? $form_options['name_after'] : '');
            ///
            ob_start();
            @include __DIR__ . '/FieldsFactory_Admin/templates/default-field.php';
            $fields_html[] = ob_get_clean();
        }
        return implode('', $fields_html);
    }


    /**
     * @param Field[]    $fields_array
     * @param null|array $field_values - set fields value, or get values from screen context
     * @param array      $form_options
     * @return string|string[]|void
     * @version 1.1
     */
    static function get_form_html($fields_array, $field_values = null, $form_options = []) {
        if ( !is_array($fields_array) || count($fields_array) == 0) return;
        ///
        ob_start();
        self::get_wp_nonce_field();
        @include __DIR__ . '/FieldsFactory_Admin/templates/default-form.php';
        $form_html = ob_get_clean();
        ///TABS COLLECTION
        $last_Field_Tab = null;
        $sections = [];
        $sections_index = 0;
        foreach ($fields_array as $key => $Field) {
            if ( !$Field instanceof Field) continue;
            if ($Field instanceof Field_Tab) {
                if ($Field->options()->label() == '' && $Field->options()->description() == '') {
                    $sections_index ++;
                    $last_Field_Tab = null;
                } else {
                    if ( !$last_Field_Tab instanceof Field_Tab) $sections_index ++;
                    $sections[$sections_index]['tabs'][$Field->global_ID()] = $Field;
                    $last_Field_Tab = $Field;
                }
            } elseif ($last_Field_Tab instanceof Field_Tab) {
                $sections[$sections_index]['fields_by_tabs'][$last_Field_Tab->global_ID()][$Field->options()->form()->order()][$Field->id()] = $Field;
            } else {
                $sections[$sections_index]['fields'][$Field->options()->form()->order()][$Field->id()] = $Field;
            }
        }
        ///
        $fields_html = '';
        foreach ($sections as $section_index => $section_data) {
            if (array_key_exists('tabs', $section_data)) {
                ob_start();
                include __DIR__ . '/FieldsFactory_Admin/templates/tabs.php';
                $fields_html .= ob_get_clean();
            } else {
                $fields_html .= self::get_form_section_fields_html($section_data['fields'], $field_values, $form_options);
            }
        }
        ///
        return str_replace('<!--fields-->', $fields_html, $form_html);
    }


    /**
     * @param $field_ID
     * @param $query
     * @return Field
     */
    static function get_Field($field_ID, $query) {
        if (is_string($field_ID)) {
            $fields = FieldsFactory::get_field_by_query($query);
            foreach ($fields as $field) {
                if ($field_ID == $field->id()) {
                    return $field;
                }
            }
        }
        return FieldsFactory::get_field('');
    }


}