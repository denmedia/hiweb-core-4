<?php

use hiweb\components\Fields\FieldsFactory;
use hiweb\components\Fields\FieldsFactory_FrontEnd;
use hiweb\components\Fields\FieldsFactory_Rows;


if ( !function_exists('get_field')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null|mixed                                         $default
     * @return mixed|null
     */
    function get_field($field_ID, $contextObject = null, $default = null) {
        return FieldsFactory_FrontEnd::get_value($field_ID, $contextObject, $default);
    }
}

if ( !function_exists('hw_get_field')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     * @return mixed|null
     */
    function _get_field($field_ID, $contextObject = null, $default = null) {
        return FieldsFactory_FrontEnd::get_value($field_ID, $contextObject, $default);
    }
}

if ( !function_exists('get_field_content')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     * @return mixed|null
     */
    function get_field_content($field_ID, $contextObject = null, $default = null) {
        return FieldsFactory_FrontEnd::get_value($field_ID, $contextObject, $default);
    }
}

if ( !function_exists('hw_get_field_content')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     * @return mixed|null
     */
    function hw_get_field_content($field_ID, $contextObject = null, $default = null) {
        return FieldsFactory_FrontEnd::get_value($field_ID, $contextObject, $default);
    }
}

if ( !function_exists('the_field_content')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     */
    function the_field_content($field_ID, $contextObject = null, $default = null) {
        echo FieldsFactory_FrontEnd::get_value($field_ID, $contextObject, $default);
    }
}

if ( !function_exists('hw_the_field_content')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     */
    function hw_the_field_content($field_ID, $contextObject = null, $default = null) {
        echo FieldsFactory_FrontEnd::get_value($field_ID, $contextObject, $default);
    }
}

if ( !function_exists('the_field')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     */
    function the_field($field_ID, $contextObject = null, $default = null) {
        echo FieldsFactory_FrontEnd::get_value($field_ID, $contextObject, $default);
    }
}

if ( !function_exists('hw_the_field')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     */
    function hw_the_field($field_ID, $contextObject = null, $default = null) {
        echo FieldsFactory_FrontEnd::get_value($field_ID, $contextObject, $default);
    }
}

if ( !function_exists('get_field_default')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     * @return mixed|null
     */
    function get_field_default($field_ID, $contextObject = null, $default = null) {
        return FieldsFactory_FrontEnd::get_Field($field_ID, $contextObject)->options()->default_value();
    }
}

if ( !function_exists('hw_get_field_default')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     * @return mixed|null
     */
    function hw_get_field_default($field_ID, $contextObject = null, $default = null) {
        return FieldsFactory_FrontEnd::get_Field($field_ID, $contextObject)->options()->default_value();
    }
}

if ( !function_exists('the_field_default')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     */
    function the_field_default($field_ID, $contextObject = null, $default = null) {
        echo FieldsFactory_FrontEnd::get_Field($field_ID, $contextObject)->options()->default_value();
    }
}

if ( !function_exists('hw_the_field_default')) {

    /**
     * @param                                                    $field_ID
     * @param null|string|int|WP_Post|WP_Term|WP_Comment|WP_User $contextObject
     * @param null                                               $default
     */
    function hw_the_field_default($field_ID, $contextObject = null, $default = null) {
        echo FieldsFactory_FrontEnd::get_Field($field_ID, $contextObject)->options()->default_value();
    }
}

if ( !function_exists('get_parent_rows')) {
    /**
     * Return parent array of the current rows
     * @return array
     */
    function get_parent_rows(): array {
        return FieldsFactory_Rows::get_parent();
    }
}

if ( !function_exists('hw_get_parent_rows')) {
    /**
     * Return parent array of the current rows
     * @return array
     */
    function hw_get_parent_rows(): array {
        return FieldsFactory_Rows::get_parent();
    }
}

////ROWS

if ( !function_exists('have_rows')) {
    /**
     * @param      $field_Id
     * @param null $objectContext
     * @return bool
     */
    function have_rows($field_Id, $objectContext = null): bool {
        return FieldsFactory_Rows::have($field_Id, $objectContext);
    }
}
if ( !function_exists('hw_have_rows')) {
    /**
     * @param      $field_Id
     * @param null $objectContext
     * @return bool
     */
    function hw_have_rows($field_Id, $objectContext = null): bool {
        return FieldsFactory_Rows::have($field_Id, $objectContext);
    }
}

if ( !function_exists('the_row')) {
    /**
     * @return mixed|null
     */
    function the_row() {
        return FieldsFactory_Rows::the();
    }
}

if ( !function_exists('hw_the_row')) {
    /**
     * @return mixed|null
     */
    function hw_the_row() {
        return FieldsFactory_Rows::the();
    }
}

if ( !function_exists('reset_rows')) {
    /**
     * @param      $fieldId
     * @param null $contextObject
     * @return bool|mixed
     */
    function reset_rows($fieldId, $contextObject = null): bool {
        return FieldsFactory_Rows::reset($fieldId, $contextObject);
    }
}

if ( !function_exists('hw_reset_rows')) {
    /**
     * @param      $fieldId
     * @param null $contextObject
     * @return bool|mixed
     */
    function hw_reset_rows($fieldId, $contextObject = null): bool {
        return FieldsFactory_Rows::reset($fieldId, $contextObject);
    }
}

if ( !function_exists('get_sub_field')) {
    /**
     * Get sub field inside have_rows iteration
     * @param string     $subFieldId
     * @param null|mixed $default
     * @return mixed|null
     */
    function get_sub_field(string $subFieldId, $default = null) {
        return FieldsFactory_Rows::get_sub_field($subFieldId, $default);
    }
}
if ( !function_exists('hw_get_sub_field')) {
    /**
     * Get sub field inside have_rows iteration
     * @param string     $subFieldId
     * @param null|mixed $default
     * @return mixed|null
     */
    function hw_get_sub_field(string $subFieldId, $default = null) {
        return FieldsFactory_Rows::get_sub_field($subFieldId, $default);
    }
}
if ( !function_exists('get_parent_field')) {
    /**
     * Get parent sub field (for outer) inside have_rows (outer) inside have_rows (inner) iteration
     * @param string     $subFieldId
     * @param null|mixed $default
     * @return mixed|null
     */
    function get_parent_field(string $subFieldId, $default = null) {
        return FieldsFactory_Rows::get_parent_field($subFieldId, $default);
    }
}
if ( !function_exists('hw_get_parent_field')) {
    /**
     * Get parent sub field (for outer) inside have_rows (outer) inside have_rows (inner) iteration
     * @param string     $subFieldId
     * @param null|mixed $default
     * @return mixed|null
     */
    function hw_get_parent_field(string $subFieldId, $default = null) {
        return FieldsFactory_Rows::get_parent_field($subFieldId, $default);
    }
}

if ( !function_exists('get_row_layout')) {
    /**
     * @return string
     */
    function get_row_layout(): string {
        return FieldsFactory_Rows::get_current()->get_layout();
    }
}

if ( !function_exists('hw_get_row_layout')) {
    /**
     * @return string
     */
    function hw_get_row_layout(): string {
        return FieldsFactory_Rows::get_current()->get_layout();
    }
}

if ( !function_exists('get_current_row')) {
    /**
     * @return mixed|null
     */
    function get_current_row() {
        return FieldsFactory_Rows::get_current()->get_current();
    }
}
if ( !function_exists('hw_get_current_row')) {
    /**
     * @return mixed|null
     */
    function hw_get_current_row() {
        return FieldsFactory_Rows::get_current()->get_current();
    }
}

if ( !function_exists('have_sub_rows')) {
    /**
     * @param string $subFieldId
     * @return bool
     */
    function have_sub_rows(string $subFieldId): bool {
        return FieldsFactory_Rows::have_sub_rows($subFieldId);
    }
}

if ( !function_exists('hw_have_sub_rows')) {
    /**
     * @param string $subFieldId
     * @return bool
     */
    function hw_have_sub_rows(string $subFieldId): bool {
        return FieldsFactory_Rows::have_sub_rows($subFieldId);
    }
}

if ( !function_exists('the_row_is_first')) {
    /**
     * Return true if current row is first
     * @return bool
     */
    function the_row_is_first(): bool {
        return FieldsFactory_Rows::get_current()->is_first();
    }
}

if ( !function_exists('the_row_is_last')) {
    /**
     * Return true if current row is last
     * @return bool
     */
    function the_row_is_last(): bool {
        return FieldsFactory_Rows::get_current()->is_last();
    }
}

if ( !function_exists('clear_field')) {
    /**
     * Clear field value
     * @param string                                         $fieldId
     * @param null|string|WP_Post|WP_User|WP_Comment|WP_Term $contextObject
     * @return false
     */
    function clear_field(string $fieldId, $contextObject = null): bool {
        return FieldsFactory::set_field_value($fieldId, $contextObject, null);
    }
}
if ( !function_exists('hw_clear_field')) {
    /**
     * Clear field value
     * @param string                                         $fieldId
     * @param null|string|WP_Post|WP_User|WP_Comment|WP_Term $contextObject
     * @return false
     */
    function hw_clear_field(string $fieldId, $contextObject = null): bool {
        return FieldsFactory::set_field_value($fieldId, $contextObject, null);
    }
}

if ( !function_exists('the_row_index')) {
    /**
     * Return current row index
     * @return int
     */
    function the_row_index(): int {
        return FieldsFactory_Rows::get_current()->get_index();
    }
}

if ( !function_exists('get_rows_count')) {
    /**
     * Return current row items count
     * @return int
     */
    function get_rows_count(): int {
        return FieldsFactory_Rows::get_current()->get_count();
    }
}
