<?php

namespace hiweb\components\Fields\Types\Repeat;


use hiweb\components\Fields\Field;
use hiweb\core\Paths\PathsFactory;
use hiweb\core\Strings;


/**
 * Class Field_Repeat
 * @package hiweb\components\Fields\Types\Repeat
 * @version 2.0
 */
class Field_Repeat extends Field {

    protected $options_class = '\hiweb\components\Fields\Types\Repeat\Field_Repeat_Options';
    protected $last_value;
    /** @var Field_Repeat_Flex[] */
    private $flexes = [];
    private $unique_id;
    private $the_name;


    public function __construct($field_ID) {
        parent::__construct($field_ID);
    }


    /**
     * @param null $set
     * @return null|string
     */
    public function get_unique_id($set = null): ?string {
        if (is_string($set)) $this->unique_id = $set;
        return $this->unique_id;
    }


    public function get_css() {
        $R = [ HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.css', __DIR__ . '/assets/repeat.css' ];
        foreach ($this->options()->get_cols() as $flex_id => $cols) {
            foreach ($cols as $col_id => $col) {
                $col_css = $col->field()->get_css();
                if (is_array($col_css)) $R = array_merge($R, $col_css); elseif (is_string($col_css)) $R[] = $col_css;
            }
        }
        return $R;
    }


    public function get_js() {
        $R = [ 'jquery-ui-sortable', HIWEB_DIR_VENDOR . '/jquery.qtip/jquery.qtip.min.js', __DIR__ . '/assets/repeat.min.js' ];
        foreach ($this->options()->get_cols() as $flex_id => $cols) {
            foreach ($cols as $col_id => $col) {
                $col_js = $col->field()->get_js();
                if (is_array($col_js)) $R = array_merge($R, $col_js); elseif (is_string($col_js)) $R[] = $col_js;
            }
        }
        return $R;
    }


    public function admin_init() {
        foreach ($this->options()->get_cols() as $flex_id => $cols) {
            foreach ($cols as $col) {
                $col->field()->admin_init();
            }
        }
        add_thickbox();
    }


    /**
     * @param string $id
     * @return Field_Repeat_Flex
     */
    public function get_flex($id = ''): Field_Repeat_Flex {
        $sanitize_id = Strings::sanitize_id($id);
        if ( !array_key_exists($sanitize_id, $this->flexes)) {
            $this->flexes[$sanitize_id] = new Field_Repeat_Flex($this, $sanitize_id);
            if ($id != '') $this->flexes[$sanitize_id]->label($id);
        }
        return $this->flexes[$sanitize_id];
    }


    /**
     * @return Field_Repeat_Flex[]
     */
    public function get_flexes(): array {
        return $this->flexes;
    }


    /**
     * @param mixed|null $value
     * @param bool       $update_meta_process
     * @return array|mixed|null
     */
    public function get_sanitize_admin_value($value, $update_meta_process = false): ?array {
        if ( !is_array($value)) {
            return [];
        }
        return $value;
    }


    /**
     * @return Field_Repeat_Options
     */
    public function options(): Field_Repeat_Options {
        return parent::options();
    }


    /**
     * @param $value_array
     * @return Field_Repeat_Value
     */
    protected function value($value_array = null): Field_Repeat_Value {
        if (is_array($value_array)) {
            $this->last_value = new Field_Repeat_Value($this, $value_array);
        }
        return $this->last_value;
    }


    //    /**
    //     * @return string
    //     */
    //    protected function the_name() {
    //        return $this->the_name;
    //    }

//    protected function get_head_html($thead = true, $handle_title = '&nbsp;') {
//        ob_start();
//        include __DIR__ . '/templates/head.php';
//        return ob_get_clean();
//    }


    public function get_admin_html($value = null, $name = null) {
        $this->value((array)$value);
        $this->the_name = $name;
        ob_start();
        include __DIR__ . '/templates/template.php';
        return ob_get_clean();
    }


    /**
     * @return bool
     */
    public function have_flex_cols(): bool {
        return count($this->options()->get_flex_ids()) > 1 || !in_array('', array_keys($this->get_flexes()));
    }


    /**
     * @return bool
     */
    public function have_cols(): bool {
        return is_array($this->options()->get_cols()) && count($this->options()->get_cols()) > 0;
    }


    static function set_buffer($value) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['hiweb-core']['components']['fields']['repeat']['buffer'] = PathsFactory::urldecode_array((array)$value);
    }


    /**
     * @return array
     */
    static function get_buffer(): array {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $value = $_SESSION['hiweb-core']['components']['fields']['repeat']['buffer'];
        return is_array($value) ? $value : [];
    }


    /**
     * @return bool
     */
    static function is_buffer_exists(): bool {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['hiweb-core']['components']['fields']['repeat']['buffer']);
    }

}