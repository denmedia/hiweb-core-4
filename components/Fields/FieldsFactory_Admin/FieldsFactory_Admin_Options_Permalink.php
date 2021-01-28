<?php

namespace hiweb\components\Fields\FieldsFactory_Admin;


use hiweb\components\Fields\Field;
use hiweb\components\Fields\Field_Options;


class FieldsFactory_Admin_Options_Permalink {

    /** @var array|Field[] */
    static $fields = [];


    /**
     * @param Field|Field_Options $field
     */
    static function _add_field($field) {
        self::$fields[$field->get_id()] = ($field instanceof Field_Options) ? $field->field() : $field;
    }


    /**
     * @return null|array
     */
    static function _update($action): ?array {
        if ($action !== 'update-permalink') return null;
        if ( !is_array($_POST) || count($_POST) === 0 || !wp_verify_nonce($_POST['hiweb-core-field-form-nonce'], 'hiweb-core-field-form-save')) return null;
        $R = [];
        foreach (self::$fields as $field) {
            $inputName = 'hiweb-option-permalink-' . $field->get_id();
            $R[$field->id()] = update_option($inputName, $field->get_sanitize_admin_value(isset($_POST[$inputName]) ? stripslashes($_POST[$inputName]) : null), true);
        }
        return $R;
    }

}