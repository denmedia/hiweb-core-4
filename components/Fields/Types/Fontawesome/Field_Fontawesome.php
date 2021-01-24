<?php

namespace hiweb\components\Fields\Types\Fontawesome;


use hiweb\components\Fields\Field;


class Field_Fontawesome extends Field {

    public function admin_init() {
        parent::admin_init();
        add_action('admin_footer', function() {
            include_once __DIR__ . '/templates/footer_scripts.php';
        });
    }


    public function get_css() {
        return [ HIWEB_DIR . '/vendor/jquery.qtip/jquery.qtip.min.css', __DIR__ . '/assets/fontawesome.css', HIWEB_DIR_VENDOR . '/font-awesome-5/css/all.min.css' ];
    }


    public function get_js() {
        return [ HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.js', __DIR__ . '/assets/fontawesome.min.js' ];
    }


    /**
     * @param null|string $value
     * @param null|string $name
     * @return false|string
     */
    public function get_admin_html($value = null, $name = null) {
        ob_start();
        include __DIR__ . '/templates/template.php';
        return ob_get_clean();
    }

}