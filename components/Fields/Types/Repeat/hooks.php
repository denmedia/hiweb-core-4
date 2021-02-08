<?php

use hiweb\components\Fields\Types\Repeat\Field_Repeat;
use hiweb\components\Fields\Types\Repeat\Field_Repeat_Row;
use hiweb\core\Paths\PathsFactory;


if (function_exists('add_action')) {

    add_action('wp_ajax_hiweb-field-repeat-get-row', function() {
        $field_global_id = $_POST['id'];
        ///
        $R = [ 'success' => false, 'filed-id' => $field_global_id ];
        //
        if ( !is_string($field_global_id) || trim($field_global_id) == '') {
            $R['message'] = __('You must specify $ _POST [id] or $ _GET [id]', 'hiweb-core-4');
        } else {
            /** @var Field_Repeat $field */
            $field = \hiweb\components\Fields\FieldsFactory::get_field($field_global_id);
            if ($field->get_id() == '') {
                $R['message'] = sprintf(__('Fields witch id[%s] not found!', 'hiweb-core-4'), $field_global_id);
                $R['console'] = \hiweb\components\Console\ConsoleFactory::$messages;
            } else if ($_POST['method'] === 'ajax_json_row') {
                if (array_key_exists('rand_id', $_POST)) $field->get_unique_id($_POST['unique_id']);
                $cols = $field->options()->get_cols();
                $R['cols'] = $cols;
                if (is_string($_POST['value']) && !array_key_exists($_POST['value'], $field->get_flexes()) && !in_array($_POST['value'], ['::paste::'])) {
                    $R['success'] = false;
                    $R['message'] = sprintf(__('Flex ID [%s] not found', 'hiweb-core-4'), $_POST['value']);
                } else {
                    $R['success'] = true;
                    ob_start();
                    $field->get_unique_id($_POST['unique_id']);
                    $rowsValue = [];
                    if ($_POST['value'] === '::paste::') {
                        $rowsValue = Field_Repeat::get_buffer();
                    } elseif (is_string($_POST['value'])) {
                        if (strpos($_POST['value'], '=') !== false) {
                            parse_str($_POST['value'], $result);
                            if (is_array($result)) foreach ($result as $fieldValues) {
                                $rowsValue = $rowsValue + $fieldValues;
                            }
                        } else {
                            $rowsValue = [ [ '_flex_row_id' => $_POST['value'] ] ];
                        }
                    } elseif (is_array($_POST['value'])) {
                        $rowsValue = PathsFactory::urldecode_array($_POST['value']);
                    }
                    ///generate html
                    ob_start();
                    foreach ($rowsValue as $row_value) {
                        $newFieldRepeatRow = new Field_Repeat_Row($field, $_POST['index'], $cols[$row_value['_flex_row_id']], $row_value);
                        $newFieldRepeatRow->the($_POST['input_name']);
                    }
                    ///
                    \hiweb\components\Console\ConsoleFactory::the();
                    $R['html'] = ob_get_clean();
                    $R['rows_value'] = $rowsValue;
                    $R['post_data'] = $_POST;
                }
                //$Field_Repeat_Value = $Field->Value( $_POST['values'] );

                //$R['data'] = $Field->ajax_html_row( \hiweb\urls::request( 'input_name' ), \hiweb\urls::request( 'index' , 0), \hiweb\urls::request( 'values' ) );
                //$R['values'] = $input->ajax_filter_values( \hiweb\urls::request( 'values' ) );
            } else if ($_POST['method'] == 'copy') {
                if (isset($_POST['value']) && is_array($_POST['value'])) {
                    $R['success'] = true;
                    $R['value'] = $_POST['value'];
                    Field_Repeat::set_buffer($_POST['value']);
                } else {
                    $R['success'] = false;
                    $R['message'] = '';
                }
            }
        }
        //
        echo json_encode($R, JSON_UNESCAPED_UNICODE);
        die;
    });
}