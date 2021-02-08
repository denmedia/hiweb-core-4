<?php

namespace hiweb\components\Fields;


use hiweb\components\Fields\Field_Options\Field_Options_Form;
use hiweb\components\Fields\Field_Options\Field_Options_Location;
use hiweb\core\Options\Options;


class Field_Options extends Options {

    /** @var Field */
    protected $Field;


    public function __construct(Field $Field) {
        parent::__construct();
        $this->Field = $Field;
    }


    /**
     * @return Field
     */
    public function field(): Field {
        return $this->Field;
    }


    /**
     * Set field label
     * @param null|string $set
     * @return $this|string
     */
    public function label($set = null) {
        return $this->_('label', $set);
    }


    /**
     * Set field description
     * @param null|string $set
     * @return $this|string
     */
    public function description($set = null) {
        return $this->_('description', $set);
    }


    /**
     * Get / set default value
     * @param null $set
     * @return array|Field_Options|mixed|null
     */
    public function default_value($set = null) {
        return $this->_('default_value', $set);
    }


    /**
     * Set field location
     * @param bool $use_last_location - put this
     * @return Field_Options_Location
     */
    public function location($use_last_location = false): Field_Options_Location {
        if ($use_last_location && FieldsFactory::$last_location_instance instanceof Field_Options_Location) {
            $this->_('location', FieldsFactory::$last_location_instance->_clone_location($this));
        }
        if ( !$this->_('location') instanceof Field_Options_Location) {
            $this->_('location', new Field_Options_Location($this));
        }
        FieldsFactory::$last_location_instance = $this->_('location');
        return $this->_('location');
    }


    /**
     * @return Field_Options_Form
     */
    public function form(): Field_Options_Form {
        if ( !$this->_('form') instanceof Field_Options_Form) {
            $this->_('form', new Field_Options_Form($this));
        }
        return $this->_('form');
    }


    /**
     * @param null   $help_text
     * @param string $image_src
     * @param string $fontawesome_icon
     * @return array|Field_Options|mixed|null
     */
    public function tooltip_help($help_text = null, $image_src = '', $fontawesome_icon = '<i class="fad fa-question-circle"></i>') {
        $data = null;
        if (is_string($help_text)) {
            $data = [ 'text' => $help_text, 'image' => $image_src, 'icon' => $fontawesome_icon ];
        }
        return $this->_('help', $data, [ 'text' => '', 'image' => '', 'icon' => $fontawesome_icon ]);
    }


    ///DEPRECATED


    /**
     * @alias $this->default_value()
     * @param null $set
     * @return array|Field_Options|mixed|null
     * @deprecated
     */
    protected function value($set = null) {
        return $this->default_value($set);
    }


    /**
     * @deprecated
     */
    protected function get_parent_field(): Field_Options {
        return $this;
    }


    /**
     * Set equation, like '[field_id = ""]' for show this field in form
     * @param null $equation
     * @return array|Field_Options|mixed|null
     */
    public function show_if($equation = null) {
        return $this->_('show_if', $equation);
    }


    /**
     * Set equation, like '[field_id = ""]' for hide this field from form
     * @param null $equation
     * @return array|Field_Options|mixed|null
     */
    public function hide_if($equation = null) {
        return $this->_('hide_if', $equation);
    }

}