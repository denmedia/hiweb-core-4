<?php

namespace hiweb\components\Fields\Types\Date;


use hiweb\components\Fields\Field;


class Field_Date extends Field {

    protected $options_class = '\hiweb\components\Fields\Types\Date\Field_Date_Options';


    public function get_css(): array {
        return [
            __DIR__ . '/assets/date.css',
            HIWEB_DIR_VENDOR . '/jquery.zabuto_calendar/zabuto_calendar.min.css',
            HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.css'
        ];
    }


    public function get_js(): array {
        return [
            HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.js',
            HIWEB_DIR_VENDOR . '/jquery.zabuto_calendar/zabuto_calendar.min.js',
            __DIR__ . '/assets/date.min.js'
        ];
    }


    /**
     * @param null $value
     * @param null $name
     * @return false|string
     */
    public function get_admin_html($value = null, $name = null) {
        ob_start();
        include __DIR__ . '/template.php';
        return ob_get_clean();
    }

}