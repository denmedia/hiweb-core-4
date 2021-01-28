<?php

use hiweb\components\Fields\FieldsFactory;
use hiweb\components\Fields\Types\Date\Field_Date;
use hiweb\components\Fields\Types\Date\Field_Date_Options;


if ( !function_exists('add_field_date')) {

    /**
     * @param $field_ID
     * @return Field_Date_Options
     */
    function add_field_date($field_ID): Field_Date_Options {
        return FieldsFactory::add_field(new Field_Date($field_ID));
    }
}