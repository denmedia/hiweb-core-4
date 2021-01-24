<?php

namespace hiweb\components\Fields\Types\Images;


use hiweb\components\Fields\Field;
use hiweb\core\Paths\PathsFactory;


class Field_Images extends Field {

    private $images;
    protected $options_class = '\hiweb\components\Fields\Types\Images\Field_Images_Options';


    /**
     * @return Field_Images_Options
     */
    public function options() {
        return parent::options();
    }


    public function get_css() {
        return [ __DIR__ . '/assets/images.css' ];
    }


    public function get_js() {
        return [ 'jquery-ui-sortable', __DIR__ . '/assets/images.min.js' ];
    }


    function admin_init() {
        wp_enqueue_media();
    }


    /**
     * @param mixed $value
     * @param false $update_meta_process
     * @return array
     * @version 1.1
     */
    public function get_sanitize_admin_value($value, $update_meta_process = false) {
        $R = [];
        if (is_array($value)) foreach ($value as $image_id) {
            if ( !is_numeric($image_id)) {
                $image_id = PathsFactory::get_attachment_id_from_url($image_id);
            }
            $test_id = intval($image_id);
            if ($test_id > 0) $R[] = $test_id;
        }
        return $R;
    }


    public function the_item($name = '', $attachment_id = 0) {
        include __DIR__ . '/templates/item.php';
    }


    public function the_item_plus($plus = 0) {
        include __DIR__ . '/templates/item_plus.php';
    }


    public function get_admin_html($value = null, $name = null) {
        ob_start();
        include __DIR__ . '/templates/template.php';
        return ob_get_clean();
    }

}