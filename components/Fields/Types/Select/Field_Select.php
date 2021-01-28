<?php

namespace hiweb\components\Fields\Types\Select;


use hiweb\components\Fields\Field;


/**
 * Class Field_Select
 * @package hiweb\components\Fields\Types\Select
 * @version 1.1
 */
class Field_Select extends Field {

    protected $options_class = '\hiweb\components\Fields\Types\Select\Field_Select_Options';


    public function get_css() {
        return [
            HIWEB_DIR_VENDOR . '/selectize.js/css/selectize.css',
            __DIR__ . '/assets/select.css'
        ];
    }


    public function get_js() {
        return [
            'jquery-ui-sortable',
            HIWEB_DIR_VENDOR . '/selectize.js/js/standalone/selectize.min.js',
            __DIR__ . '/assets/select.min.js'
        ];
    }


    /**
     * @return Field_Select_Options
     */
    public function options() {
        return parent::options();
    }


    public function get_admin_html($value = null, $name = null) {
        ob_start();
        include __DIR__ . '/template.php';
        return ob_get_clean();
    }

}