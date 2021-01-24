<?php

namespace hiweb\components\Fields\Field_Options;


use hiweb\components\Fields\Field_Options;
use hiweb\components\Fields\FieldsFactory;
use hiweb\components\Fields\FieldsFactory_Admin;
use hiweb\core\Options\Options;


class Field_Options_Location extends Options {

	public function __construct($parent_OptionsObject = null) {
		parent::__construct( $parent_OptionsObject );
	}


	public function __clone() {
		$this->Options = clone $this->Options;
		if ( $this->_( 'post_type' ) instanceof Field_Options_Location_PostType ) {
			$this->_( 'post_type', clone $this->_( 'post_type' ) );
		}
		if ( $this->_( 'taxonomy' ) instanceof Field_Options_Location_Taxonomy ) {
			$this->_( 'taxonomy', clone $this->_( 'taxonomy' ) );
		}
		if ( $this->_( 'user' ) instanceof Field_Options_Location_User ) {
			$this->_( 'user', clone $this->_( 'user' ) );
		}
		if ( $this->_( 'form' ) instanceof Field_Options_Form ) {
			$this->_( 'form', clone $this->_( 'form' ) );
		}
		if ( $this->_( 'form' ) instanceof Field_Options_Form ) {
			$this->_( 'form', clone $this->_( 'form' ) );
		}
	}


	/**
	 * @param Field_Options $target_Field_Options
	 *
	 * @return Field_Options_Location
	 */
	protected function clone_location(Field_Options $target_Field_Options) {
		$new_location = clone $this;
		$new_location->parent_OptionsObject = $target_Field_Options;
		if ( $new_location->options() != '' && function_exists('\register_setting') ) {
			\register_setting( $new_location->options(), 'hiweb-option-' . $new_location->options() . '-' . $target_Field_Options->field()->id() );
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
	 * @param null|string|string[] $post_type
	 *
	 * @return Field_Options_Location_PostType
	 */
	public function posts($post_type = null) {
		if ( !$this->_( 'post_type' ) instanceof Field_Options_Location_PostType ) {
			$this->_( 'post_type', new Field_Options_Location_PostType( $this ) );
			if ( !is_null( $post_type ) )
				$this->posts()->post_type( $post_type );
			FieldsFactory::$fieldIds_by_locations['post_type'][$this->getParent_OptionsObject()->field()->global_id()] = $this->getParent_OptionsObject()->field();
		}
		return $this->_( 'post_type' );
	}


	/**
	 * @return Field_Options_Location_NavMenu
	 */
	public function nav_menu() {
		if ( !$this->_( 'nav_menu' ) instanceof Field_Options_Location_NavMenu ) {
			$this->_( 'nav_menu', new Field_Options_Location_NavMenu( $this ) );
			FieldsFactory::$fieldIds_by_locations['nav_menu'][$this->getParent_OptionsObject()->field()->global_id()] = $this->getParent_OptionsObject()->field();
		}
		return $this->_( 'nav_menu' );
	}


	/**
	 * @param null|string|string[] $taxonomy
	 *
	 * @return Field_Options_Location_Taxonomy
	 */
	public function taxonomies($taxonomy = null) {
		if ( !$this->_( 'taxonomy' ) instanceof Field_Options_Location_Taxonomy ) {
			$this->_( 'taxonomy', new Field_Options_Location_Taxonomy( $this ) );
			if ( is_string( $taxonomy ) )
				$taxonomy = [ $taxonomy ];
			if ( is_array( $taxonomy ) )
				$this->taxonomies()->taxonomy( $taxonomy );
			FieldsFactory::$fieldIds_by_locations['taxonomy'][$this->getParent_OptionsObject()->field()->global_id()] = $this->getParent_OptionsObject()->field();
		}
		return $this->_( 'taxonomy' );
	}


	/**
	 * @return Field_Options_Location_User
	 */
	public function users() {
		if ( !$this->_( 'user' ) instanceof Field_Options_Location_User ) {
			$this->_( 'user', new Field_Options_Location_User( $this ) );
			FieldsFactory::$fieldIds_by_locations['user'][$this->getParent_OptionsObject()->field()->global_id()] = $this->getParent_OptionsObject()->field();
		}
		return $this->_( 'user' );
	}


	/**
	 * @param null|string $sectionTitle - set section title (not ID), section is created automatically based on the specified title
	 *
	 * @return Field_Options_Location_Customize
	 */
	public function customize($sectionTitle = null) {
		if ( !$this->_( 'customize' ) instanceof Field_Options_Location_Customize ) {
			$this->_( 'customize', new Field_Options_Location_Customize( $this ) );
		}
		if ( is_string( $sectionTitle ) && $sectionTitle !== '' ) {
			$this->_( 'customize' )->section( $sectionTitle );
		}
		return $this->_( 'customize' );
	}


	/**
	 * @param null $page_slug
	 *
	 * @return array|Field_Options_Location|mixed|null
	 */
	public function options($page_slug = null) {
		if ( !is_null( $page_slug ) ) {
			$this->_( 'options', $page_slug );
			FieldsFactory::$fieldIds_by_locations['options'][$page_slug][$this->getParent_OptionsObject()->field()->global_id()] = $this->getParent_OptionsObject()->field();
		}
		if ( is_string( $page_slug ) && $this->getParent_OptionsObject()->field()->get_allow_save_field() && function_exists('\register_setting') ) {
			\register_setting( $page_slug, 'hiweb-option-' . $page_slug . '-' . $this->getParent_OptionsObject()->field()->id() );
		}
		return $this->_( 'options', $page_slug );
	}


	///ALIAS


	/**
	 * @param null $page_slug
	 *
	 * @return string
	 * @alias $this->Options
	 */
	public function admin_menus($page_slug = null) {
		return $this->options( $page_slug );
	}


	/**
	 * @param null $post_type
	 *
	 * @return Field_Options_Location_PostType
	 */
	protected function Post_Types($post_type = null) {
		return $this->posts( $post_type );
	}


}