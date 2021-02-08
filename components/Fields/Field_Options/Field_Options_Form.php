<?php

namespace hiweb\components\Fields\Field_Options;


use hiweb\core\Options\Options;


class Field_Options_Form extends Options {


    public function __construct($parent_OptionsObject = null) {
        parent::__construct($parent_OptionsObject);
    }


    /**
     * @return Field_Options_Location
     */
    protected function getParent_OptionsObject(): Field_Options_Location {
        return parent::getParent_OptionsObject();
    }


    /**
     * @param null $set
     * @return array|Field_Options_Form|mixed|null
     */
    public function order($set = null) {
        return $this->_('order', $set, 10);
    }


    /**
     * @param null $set
     * @return $this
     * @deprecated
     */
    protected function width($set = null) {
        return $this;
    }


    /**
     * Occupies 1/2 of the window (on medium and large screens)
     * @return array|Field_Options_Form|mixed|null
     */
    public function half() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Occupies 1/3 of the window (on medium and large screens)
     * @return array|Field_Options_Form|mixed|null
     */
    public function third() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Occupies 2/3 of the window (on medium and large screens)
     * @return array|Field_Options_Form|mixed|null
     */
    public function two_third() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Occupies 1/4 of the window (on medium and large screens)
     * @return array|Field_Options_Form|mixed|null
     */
    public function quarter() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Occupies 3/4 of the window (on medium and large screens)
     * @return array|Field_Options_Form|mixed|null
     */
    public function three_quarter() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Occupies 1/5 of the window (on medium and large screens)
     * @return array|Field_Options_Form|mixed|null
     */
    public function fifth() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Occupies 2/5 of the window (on medium and large screens)
     * @return array|Field_Options_Form|mixed|null
     */
    public function two_fifth() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Occupies 3/5 of the window (on medium and large screens)
     * @return array|Field_Options_Form|mixed|null
     */
    public function three_fifth() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Occupies 4/5 of the window (on medium and large screens)
     * @return array|Field_Options_Form|mixed|null
     */
    public function four_fifths() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Occupies the rest of the column width
     * @return array|Field_Options_Form|mixed|null
     */
    public function full() {
        return $this->_('width', __FUNCTION__);
    }


    /**
     * Set TRUE/FALSE for show or hide field label
     * @param null|boolean $set
     * @return array|Field_Options_Form|mixed|null
     */
    public function show_labels($set = null) {
        return $this->_('show_label', $set, true);
    }




    //		/**
    //		 * @return Field_Options_Location
    //		 */
    //		public function get_parent_field(){
    //			return $this->getParent_OptionsObject()->getRoot_OptionsObject();
    //		}

    //		/**
    //		 * @return Field_Options_Location
    //		 */
    //		public function Location( $use_last_location = null ){
    //			return $this->get_parent_field->Location( $use_last_location );
    //		}

}