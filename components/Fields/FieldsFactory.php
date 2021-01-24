<?php

namespace hiweb\components\Fields;


use hiweb\components\Fields\Field_Options\Field_Options_Location;
use hiweb\components\Structures\StructuresFactory;
use hiweb\core\Cache\CacheFactory;
use hiweb\core\hidden_methods;
use WP_Comment;
use WP_Post;
use WP_Term;
use WP_User;


/**
 * Class FieldsFactory
 * @package hiweb\components\Fields
 * @version 1.2
 */
class FieldsFactory {

    use hidden_methods;


    private static $fields = [];
    static $fieldIds_by_locations = [];

    /** @var Field_Options_Location */
    static $last_location_instance;


    /**
     * @param       $fieldId
     * @return bool|string
     */
    static private function get_free_global_id($fieldId) {
        if ( !array_key_exists($fieldId, self::$fields)) return $fieldId;
        for ($count = 0; $count < 999; $count ++) {
            $count = sprintf('%03u', $count);
            $input_name_id = $fieldId . '_' . $count;
            if ( !isset(self::$fields[$input_name_id])) return $input_name_id;
        }
        return false;
    }


    /**
     * @param Field  $Field
     * @param string $field_options_class - set options class name or leave them
     * @return mixed|Field_Options - return \hiweb\components\Fields\Field_Options or similar options instance
     */
    static function add_field(Field $Field, $field_options_class = '\hiweb\components\Fields\Field_Options') {
        $global_ID = self::get_free_global_id($Field->id());
        $Field->global_id = $global_ID;
        self::$fields[$global_ID] = $Field;
        CacheFactory::remove_group('\hiweb\components\Fields\FieldsFactory::get_field_by_query', false);
        return $Field->options();
    }


    /**
     * @return array|Field[]
     */
    static function get_fields() {
        return self::$fields;
    }


    /**
     * Return Field[] by id, use * symbol
     * @param string $field_ID
     * @return array|Field[]
     */
    static function get_search_fields_by_id($field_ID = 'field*') {
        $R = [];
        $field_ID = strtr($field_ID, [ '*' => '.*', '-' => '\-' ]);
        foreach (self::get_fields() as $id => $field) {
            if (preg_match('/^' . $field_ID . '$/i', $id) > 0) $R[$field->global_id()] = $field;
        }
        return $R;
    }


    /**
     * Return filed by id or dummy filed
     * @param $field_global_ID
     * @return Field
     */
    static function get_field($field_global_ID) {
        if (array_key_exists($field_global_ID, self::$fields)) {
            return self::$fields[$field_global_ID];
        } else {
            return CacheFactory::get('dummy_field_instance', __CLASS__, function() {
                return new Field('');
            })->get_value();
        }
    }


    /**
     * @param array       $fieldLocation
     * @param array       $locationQuery
     * @param null|string $parent_key
     * @param string|null $parent_operator
     * @return array
     * @version 1.1
     */
    static function diff($locationQuery, $fieldLocation, $parent_key = null, $parent_operator = '&') {
        $R = [];
        ///Prepare Arrays
        $is_end_of_branch = true;
        $operator_by_key = [];
        $locationQuery_emptyKeys = [];
        $locationQuery_filtered = [];
        $fieldLocation_filtered = [];
        foreach ([ 'locationQuery' => $locationQuery, 'fieldLocation' => $fieldLocation ] as $name => $arr) {
            $tmp_result_arr = [];
            foreach ($arr as $key => $value) {
                if ( !is_numeric($key)) {
                    $is_end_of_branch = false;
                    ///
                    $tmp_operator = substr($key, 0, 1);
                    if (in_array($tmp_operator, [ '&', '|', '!', '~', '?' ])) {
                        $tmp_key = substr($key, 1);
                    } else {
                        $tmp_key = $key;
                        $tmp_operator = '&';
                    }
                    $operator_by_key[$tmp_key] = $tmp_operator;
                    $tmp_result_arr[$tmp_key] = (array)$value;
                    if ($name == 'locationQuery' && count((array)$value) == 0) {
                        $locationQuery_emptyKeys[] = $tmp_key;
                    }
                } else {
                    $tmp_result_arr[] = $value;
                }
            }
            switch($name) {
                case 'locationQuery' :
                    $locationQuery_filtered = $tmp_result_arr;
                    break;
                case 'fieldLocation' :
                    $fieldLocation_filtered = $tmp_result_arr;
                    break;
            }
        }
        ///Compare arrays
        $matches = 0;
        $mismatches = 0;
        foreach ($fieldLocation_filtered as $key => $value) {
            if ($is_end_of_branch) {
                if ( !in_array($value, $locationQuery_filtered)) {
                    $R[] = $value;
                    $mismatches ++;
                } else {
                    $matches ++;
                }
            } else {
                if (array_key_exists($key, $locationQuery_filtered) && count($locationQuery_filtered[$key]) > 0 && count($value) > 0) {
                    $tmp_result_arr = self::diff($locationQuery_filtered[$key], $fieldLocation_filtered[$key], $key, $operator_by_key[$key]);
                    if (count($tmp_result_arr) > 0) {
                        $R[$key] = $tmp_result_arr;
                        $mismatches ++;
                    } else {
                        $matches ++;
                    }
                } else {
                    if (is_null($parent_key) && !array_key_exists($key, $locationQuery_filtered)) {
                        $R[$key] = $value;
                        $mismatches ++;
                    } else {
                        $matches ++;
                    }
                }
            }
        }
        ///Empty Location Query
        foreach ($locationQuery_emptyKeys as $key) {
            if ( !array_key_exists($key, $fieldLocation_filtered) || count($fieldLocation_filtered[$key]) == 0) {
                $R[$key] = [];
                $mismatches ++;
            }
        }
        ///Operator Compare
        switch($parent_operator) {
            case '|':
                if ($matches > 0) $R = [];
                break;
            case '!':
                if ($mismatches > 0) $R = [];
                break;
            case '~':
                if ($matches == 0 && $mismatches > 0) $R = [];
                break;
            case '?':
                if (array_key_exists($parent_key, $locationQuery_filtered)) $R = [];
                break;
            default:
                //do nothing
                if ($mismatches == 0) $R = [];
                break;
        }
        return $R;
    }


    /**
     * @param $locationQuery
     * @return Field[]
     */
    static function get_field_by_query($locationQuery) {
        return CacheFactory::get(json_encode($locationQuery), '\hiweb\components\Fields\FieldsFactory::get_field_by_query', function() {
            $locationQuery = func_get_arg(0);
            if (is_string($locationQuery)) $locationQuery = json_decode($locationQuery, true);
            $Fields = [];
            foreach (FieldsFactory::get_fields() as $global_id => $Field) {
                $field_location_options = $Field->options()->location()->_get_optionsCollect();
                if (count($field_location_options) == 0) continue;
                $diff = self::diff($locationQuery, $field_location_options);
                if (count($diff) == 0) {
                    $Fields[$Field->id()] = $Field;
                }
            }
            return $Fields;
        }, [ $locationQuery ])->get_value();
    }


    /**
     * @param null|WP_Post|WP_Term|WP_User|string $objectContext
     * @return array|object|WP_Post|null
     */
    static function get_sanitize_objectContext($objectContext = null) {
        ///prepare object
        if (is_null($objectContext)) {
            if (function_exists('get_queried_object')) {
                if (get_queried_object() instanceof \WP_Post_Type && get_queried_object()->name == 'product' && function_exists('WC')) {
                    return get_post(get_option('woocommerce_shop_page_id'));
                } else {
                    return get_queried_object();
                }
            }
        } elseif (is_numeric($objectContext)) {
            return get_post($objectContext);
        }
        return $objectContext;
    }


    /**
     * @param null|WP_Post|WP_Term|WP_User|string $contextObject
     * @return array|array[]|string[]
     */
    static function get_query_from_contextObject($contextObject = null): array {
        $R = [];
        $contextObject = self::get_sanitize_objectContext($contextObject);
        ///
        if ($contextObject instanceof WP_Post) {
            if ($contextObject->post_type == 'nav_menu_item') {
                $R = [
                    'nav_menu' => [
                        'ID' => $contextObject->ID,
                    ],
                ];
            } else {
                $R = [
                    'post_type' => [
                        'ID' => $contextObject->ID,
                        'post_type' => $contextObject->post_type,
                        'post_name' => $contextObject->post_name,
                        'post_status' => $contextObject->post_status,
                        'comment_status' => $contextObject->comment_status,
                        'post_parent' => $contextObject->post_parent,
                        'has_taxonomy' => $contextObject->has_taxonomy,
                        'front_page' => StructuresFactory::get_front_page_id() == $contextObject->ID,
                    ],
                ];
            }
        } elseif ($contextObject instanceof \WP_Term) {
            $R = [
                'taxonomy' => [
                    'term_id' => $contextObject->term_id,
                    'term_taxonomy_id' => $contextObject->term_taxonomy_id,
                    'name' => $contextObject->name,
                    'taxonomy' => $contextObject->taxonomy,
                    'slug' => $contextObject->slug,
                    'count' => $contextObject->count,
                    'parent' => $contextObject->parent,
                    'term_group' => $contextObject->term_group,
                ],
            ];
        } elseif (is_string($contextObject)) {
            $R = [
                'options' => $contextObject,
            ];
        }
        return $R;
    }


    /**
     * Set / clear field value, return NULL if field not exists, FALSE - if failure on set value, TRUE - if every ok
     * @param                                                $field_ID
     * @param null|string|WP_Post|WP_User|WP_Comment|WP_Term $objectContext
     * @param null|mixed                                     $setValue - new value
     * @return false
     */
    static function set_field_value($field_ID, $objectContext = null, $setValue = null) {
        $contextObject_sanitize = FieldsFactory::get_sanitize_objectContext($objectContext);
        $fields = self::get_field_by_query(self::get_query_from_contextObject($contextObject_sanitize));
        if (array_key_exists($field_ID, $fields)) {
            $field = $fields[$field_ID];
            if ($contextObject_sanitize instanceof WP_Post) {
                if (is_null($setValue)) {
                    return delete_post_meta($contextObject_sanitize->ID, $field->id());
                } else {
                    return update_post_meta($contextObject_sanitize->ID, $field->id(), $setValue);
                }
            } elseif ($contextObject_sanitize instanceof WP_Term) {
                if (is_null($setValue)) {
                    return delete_term_meta($contextObject_sanitize->term_id, $field->id());
                } else {
                    return update_term_meta($contextObject_sanitize->term_id, $field->id(), $setValue);
                }
            } elseif ($contextObject_sanitize instanceof WP_User) {
                if (is_null($setValue)) {
                    return delete_user_meta($contextObject_sanitize->ID, $field->id());
                } else {
                    return update_user_meta($contextObject_sanitize->ID, $field->id(), $setValue);
                }
            } elseif ($contextObject_sanitize instanceof WP_Comment) {
                if (is_null($setValue)) {
                    return delete_comment_meta($contextObject_sanitize->ID, $field->id());
                } else {
                    return update_comment_meta($contextObject_sanitize->ID, $field->id(), $setValue);
                }
            } elseif (is_string($contextObject_sanitize)) {
                $option_name = 'hiweb-option-' . $contextObject_sanitize . '-' . $field->id();
                if (is_null($setValue)) {
                    return delete_option($option_name);
                } else {
                    return update_option($option_name, $setValue);
                }
            }
        }
        return false;
    }

}