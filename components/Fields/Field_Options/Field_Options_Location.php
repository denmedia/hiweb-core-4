<?php

namespace hiweb\components\Fields\Field_Options;


use hiweb\components\Fields\Field;
use hiweb\components\Fields\Field_Options;
use hiweb\components\Fields\FieldsFactory;
use hiweb\components\Fields\FieldsFactory_Admin;
use hiweb\components\Fields\FieldsFactory_Admin\FieldsFactory_Admin_Options_Permalink;
use hiweb\core\Options\Options;
use function register_setting;


class Field_Options_Location extends Options {

    public function __construct($parent_OptionsObject = null) {
        parent::__construct($parent_OptionsObject);
    }


    public function __clone() {
        $this->Options = clone $this->Options;
        if ($this->_('post_type') instanceof Field_Options_Location_PostType) {
            $this->_('post_type', clone $this->_('post_type'));
        }
        if ($this->_('taxonomy') instanceof Field_Options_Location_Taxonomy) {
            $this->_('taxonomy', clone $this->_('taxonomy'));
        }
        if ($this->_('user') instanceof Field_Options_Location_User) {
            $this->_('user', clone $this->_('user'));
        }
        if ($this->_('form') instanceof Field_Options_Form) {
            $this->_('form', clone $this->_('form'));
        }
        if ($this->_('form') instanceof Field_Options_Form) {
            $this->_('form', clone $this->_('form'));
        }
    }


    /**
     * @param Field_Options $target_Field_Options
     * @return Field_Options_Location
     */
    public function _clone_location(Field_Options $target_Field_Options): Field_Options_Location {
        $new_location = clone $this;
        $new_location->parent_OptionsObject = $target_Field_Options;
        if ((string)$new_location->options() != '' && $target_Field_Options->field()->get_allow_save_field() && function_exists('\register_setting')) {
            if ($new_location->options() === 'permalink') {
                FieldsFactory_Admin_Options_Permalink::_add_field($target_Field_Options->field());
            } else {
                register_setting($new_location->options(), FieldsFactory_Admin::_get_prepend_name_by_options($new_location->options()) . '-' . $target_Field_Options->field()->id(), [ 'sanitize_callback' => [ $target_Field_Options->field(), 'get_sanitize_admin_value' ] ]);
            }
        }
        return $new_location;
    }


    /**
     * @return Field_Options
     */
    protected function getParent_OptionsObject() {
        return parent::getParent_OptionsObject();
    }


    /**
     * @param null|int $set
     * @return array|Field_Options_Location|mixed|null
     */
    public function order($set = null){
        return $this->_('order', $set, 10);
    }


    /**
     * @param null|string|string[] $post_type
     * @return Field_Options_Location_PostType
     */
    public function posts($post_type = null): Field_Options_Location_PostType {
        if ( !$this->_('post_type') instanceof Field_Options_Location_PostType) {
            $this->_('post_type', new Field_Options_Location_PostType($this));
            if ( !is_null($post_type)) $this->posts()->post_type($post_type);
            FieldsFactory::$fieldIds_by_locations['post_type'][$this->getParent_OptionsObject()->field()->get_global_id()] = $this->getParent_OptionsObject()->field();
        }
        return $this->_('post_type');
    }


    /**
     * @return Field_Options_Location_NavMenu
     */
    public function nav_menu(): Field_Options_Location_NavMenu {
        if ( !$this->_('nav_menu') instanceof Field_Options_Location_NavMenu) {
            $this->_('nav_menu', new Field_Options_Location_NavMenu($this));
            FieldsFactory::$fieldIds_by_locations['nav_menu'][$this->getParent_OptionsObject()->field()->get_global_id()] = $this->getParent_OptionsObject()->field();
        }
        return $this->_('nav_menu');
    }


    /**
     * @param null|string|string[] $taxonomy
     * @return Field_Options_Location_Taxonomy
     */
    public function taxonomies($taxonomy = null): Field_Options_Location_Taxonomy {
        if ( !$this->_('taxonomy') instanceof Field_Options_Location_Taxonomy) {
            $this->_('taxonomy', new Field_Options_Location_Taxonomy($this));
            if (is_string($taxonomy)) $taxonomy = [ $taxonomy ];
            if (is_array($taxonomy)) $this->taxonomies()->taxonomy($taxonomy);
            FieldsFactory::$fieldIds_by_locations['taxonomy'][$this->getParent_OptionsObject()->field()->global_id()] = $this->getParent_OptionsObject()->field();
        }
        return $this->_('taxonomy');
    }


    /**
     * @return Field_Options_Location_User
     */
    public function users(): Field_Options_Location_User {
        if ( !$this->_('user') instanceof Field_Options_Location_User) {
            $this->_('user', new Field_Options_Location_User($this));
            FieldsFactory::$fieldIds_by_locations['user'][$this->getParent_OptionsObject()->field()->global_id()] = $this->getParent_OptionsObject()->field();
        }
        return $this->_('user');
    }


    /**
     * @param null|string $sectionTitle - set section title (not ID), section is created automatically based on the specified title
     * @return Field_Options_Location_Customize
     */
    public function customize($sectionTitle = null): Field_Options_Location_Customize {
        if ( !$this->_('customize') instanceof Field_Options_Location_Customize) {
            $this->_('customize', new Field_Options_Location_Customize($this));
        }
        if (is_string($sectionTitle) && $sectionTitle !== '') {
            $this->_('customize')->section($sectionTitle);
        }
        return $this->_('customize');
    }


    /**
     * @return Field
     */
    public function field(): Field {
        return $this->getParent_OptionsObject()->field();
    }


    /**
     * @param null|string $pageSlug - set options page slug
     *                              Use default WP pages: permalink, media, discussion, reading, writing, general
     * @return array|Field_Options_Location|mixed|null
     */
    public function options($pageSlug = null) {
        if ( !is_null($pageSlug)) {
            $this->_('options', $pageSlug);
            FieldsFactory::$fieldIds_by_locations['options'][$pageSlug][$this->getParent_OptionsObject()->field()->get_global_id()] = $this->field();
        }
        if (is_string($pageSlug) && $this->getParent_OptionsObject()->field()->get_allow_save_field() && function_exists('\register_setting')) {
            if ($pageSlug === 'permalink') {
                FieldsFactory_Admin_Options_Permalink::_add_field($this->field());
            } else {
                register_setting($pageSlug, FieldsFactory_Admin::_get_prepend_name_by_options($pageSlug) . '-' . $this->field()->get_id(), [ 'sanitize_callback' => [ $this->field(), 'get_sanitize_admin_value' ] ]);
            }
        }
        return $this->_('options', $pageSlug);
    }


    ///ALIAS


    /**
     * @param null $page_slug
     * @return string
     * @alias $this->Options
     */
    public function admin_menus($page_slug = null) {
        return $this->options($page_slug);
    }


    /**
     * @param null $post_type
     * @return Field_Options_Location_PostType
     */
    protected function Post_Types($post_type = null): Field_Options_Location_PostType {
        return $this->posts($post_type);
    }


}