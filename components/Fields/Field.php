<?php

namespace hiweb\components\Fields;


use hiweb\components\Console\ConsoleFactory;
use hiweb\core\ArrayObject\ArrayObject;
use hiweb\core\hidden_methods;
use hiweb\core\Strings;


class Field {

    use hidden_methods;


    /** @var string */
    private $id;
    /** @var string */
    protected $global_id;
    /** @var */
    protected $options_class = '\hiweb\components\Fields\Field_Options';
    protected $id_empty = false;
    protected $debug_backtrace;


    public function __construct($field_ID = null) {
        $this->debug_backtrace = debug_backtrace();
        if ( !is_string($field_ID)) {
            $field_ID = strtolower(basename(str_replace('\\', '/', get_called_class()))) . '_' . Strings::rand(5);
            $this->id_empty = true;
        }
        $this->id = $field_ID;
        if (class_exists($this->options_class)) {
            $this->options_class = new $this->options_class($this);
        }
        if ( !$this->options_class instanceof Field_Options) {
            ConsoleFactory::add('this is not FieldOptions instance!', 'warn', __CLASS__, $this->options_class, true);
            $this->options_class = new \hiweb\components\Fields\Field_Options($this);
        }
    }


    /**
     * Return url, string or some array to css styles
     * @return array|string
     */
    public function get_css() {
        return [];
    }


    /**
     * Return url, string or some array to js scripts
     * @return array|string
     */
    public function get_js() {
        return [];
    }


    /**
     * Init function once by admin page (where field is printed, not ajax)
     */
    public function admin_init() {
        ///
    }


    /**
     * @return Field_Options|mixed
     */
    public function options() {
        if ($this->options_class instanceof Field_Options) {
            return $this->options_class;
        } elseif (class_exists($this->options_class)) {
            return new $this->options_class($this);
        } else {
            ConsoleFactory::add('Error load options class for field', 'warn', __CLASS__, $this->options_class, true);
            return new Field_Options($this);
        }
    }


    /**
     * Return field ID
     * @return string
     */
    public function id(): string {
        return $this->id;
    }


    /**
     * Return field ID
     * @alias ID()
     * @return string
     */
    public function get_id(): string {
        return $this->id();
    }


    /**
     * Return field global ID
     * @return string
     * @deprecated use get_global_id()
     */
    public function global_id(): string {
        return $this->global_id;
    }


    /**
     * Return global field id
     * @return string
     */
    public function get_global_id(): string {
        return $this->global_id;
    }


    /**
     * Return sanitize input name
     * @param null $name
     * @return null|string
     */
    public function get_sanitize_admin_name($name = null): ?string {
        if ( !is_string($name) || trim($name) == '') return $this->id();
        return $name;
    }


    /**
     * @param null|mixed $value
     * @param bool       $update_meta_process - if TRUE, this is mean meta save process
     * @return null|mixed
     */
    public function get_sanitize_admin_value($value, $update_meta_process = false) {
        return $value;
    }


    /**
     * @param $value
     * @return bool
     */
    public function get_allow_save_field($value = null): bool {
        return true && !$this->id_empty;
    }


    /**
     * @param array       $append_tags
     * @param null|string $name
     * @return string
     */
    protected function get_admin_wrap_tag_properties($append_tags = [], $name = null): string {
        $join_tags = [ 'class' => ' ' ];
        $called_class = str_replace('\\', '/', get_called_class());
        $tags = get_array([
            'class' => 'hiweb-' . Strings::sanitize_id(basename($called_class)),
            'data-field-init' => '0',
            'data-field-id' => $this->get_id(),
            'data-rand-id' => 'hiweb-' . Strings::sanitize_id(basename($called_class)) . '-' . $this->get_id() . '-' . Strings::rand(5),
            'data-field-global_id' => $this->get_global_id(),
            'data-field-input_name' => $this->get_sanitize_admin_name(( !is_string($name) || trim($name) === '') ? $this->get_id() : $name)
        ]);
        if(!empty($this->options()->show_if())) $tags->push('data-field-show_if', $this->options()->show_if());
        if(!empty($this->options()->hide_if())) $tags->push('data-field-hide_if', $this->options()->hide_if());
        if (is_array($append_tags)) $append_tags = get_array($append_tags);
        if ($append_tags instanceof ArrayObject) foreach ($append_tags->get() as $key => $val) {
            if ($tags->is_key_exists($key) && array_key_exists($key, $join_tags)) {
                $tags->set_value($key, join($join_tags[$key], array_merge((array)$tags->_($key), (array)$val)));
            } else {
                $tags->set_value($key, $val);
            }
        }
        return $tags->get_as_tag_attributes(false);
    }


    /**
     * Return name="..." tag for inputs, use for repeat field auto change name or other...
     * @param null|string          $name
     * @param null|string|string[] $name_append
     * @return array|string
     */
    public function get_admin_input_tags_name_properties($name = null, $name_append = null) {
        $tags = get_array();
        $name = $this->get_sanitize_admin_name($name);
        $tags->push('data-input_name_source', $name);
        if (is_string($name_append)) $name_append = (array)$name_append;
        if (is_array($name_append)) {
            $name_append = join('', $name_append);
            $name .= $name_append;
            $tags->push('data-input_name_append', $name_append);
        }
        $tags->push('name', $name);
        return $tags->get_as_tag_attributes();
    }


    /**
     * @param null|mixed  $value
     * @param null|string $name
     * @return false|string
     */
    public function get_admin_html($value = null, $name = null) {
        $input_name = $this->get_sanitize_admin_name($name);
        return '<div ' . $this->get_admin_wrap_tag_properties([], $input_name) . '><input type="text" name="' . htmlentities($input_name) . '" value="' . htmlentities($this->get_sanitize_admin_value($value)) . '" /></div>';
    }


    /**
     * @param null $wp_object
     * @param null $object_id
     * @param null $columns_name
     * @return false|string
     */
    public function get_admin_columns_html($wp_object = null, $object_id = null, $columns_name = null) {
        ob_start();
        if ($wp_object instanceof \WP_Post) {
            $value = get_post_meta($wp_object->ID, $this->id(), true);
        } elseif ($wp_object instanceof \WP_Term) {
            $value = get_term_meta($wp_object->term_id, $this->id(), true);
        } elseif ($wp_object instanceof \WP_User) {
            $value = get_user_meta($wp_object->ID, $this->id(), true);
        } elseif ($wp_object instanceof \WP_Comment) {
            $value = get_comment_meta($wp_object->comment_ID, $this->id(), true);
        }
        echo '<div class="hiweb-' . Strings::sanitize_id(basename(str_replace('\\', '/', get_called_class()))) . '-column-' . $this->id() . '">' . $value . '</div>';
        return ob_get_clean();
    }


}