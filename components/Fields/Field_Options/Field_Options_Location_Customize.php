<?php

namespace hiweb\components\Fields\Field_Options;


use hiweb\core\Options\Options;


/**
 * Class for set field location to theme customize admin page
 *
 * @package hiweb\components\Fields\Field_Options
 */
class Field_Options_Location_Customize extends Options {

	public function __construct($parent_OptionsObject = null) {
		parent::__construct( $parent_OptionsObject );
	}

	/**
	 * Set you'r own section title. If a section with this title does not exist, it will be created automatically
	 * Section is created automatically based on the specified title
	 *
	 * @param null|string $set_sectionTitle
	 *
	 * @return Field_Options_Location_Customize_Sections
	 */
	public function section($set_sectionTitle = null) {
		if ( !$this->_( 'sections' ) instanceof Field_Options_Location_Customize_Sections ) {
			$this->_( 'sections', new Field_Options_Location_Customize_Sections( $this ) );
		}
		if ( is_string( $set_sectionTitle ) )
			$this->_( 'sections' )->title( $set_sectionTitle );
		return $this->_( 'sections' );
	}


	/**
	 * Set default WP section 'title_tagline'
	 *
	 * @return Field_Options_Location_Customize
	 */
	public function section_title_tagline() {
		$this->section( 'title_tagline' );
		return $this;
	}


	/**
	 * Set default WP section 'colors'
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function section_colors() {
		$this->section( 'colors' );
		return $this;
	}


	/**
	 * Set default WP section 'header_image'
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function section_header_image() {
		$this->section( 'header_image' );
		return $this;
	}


	/**
	 * Set default WP section 'background_image'
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function section_background_image() {
		$this->section( 'background_image' );
		return $this;
	}


	/**
	 * Set default WP section 'nav'
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function section_nav() {
		$this->section( 'nav' );
		return $this;
	}


	/**
	 * Set default WP section 'static_front_page'
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function section_static_front_page() {
		$this->section( 'static_front_page' );
		return $this;
	}


	/**
	 * Set type to theme mod
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function type_theme_mod() {
		return $this->_( 'type', 'theme_mod' );
	}


	/**
	 * Set type to option
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function type_option() {
		return $this->_( 'type', 'option' );
	}


	/**
	 * Set capability
	 *
	 * @param null|string $set - set user capability, like 'edit_theme_options'
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function capability($set = null) {
		return $this->_( 'capability', $set );
	}


	/**
	 * @param null|string|string[] $set
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function theme_supports($set = null) {
		return $this->_( 'theme_supports', $set );
	}


	/**
	 * Set transport property to 'refresh'
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function transport_refresh() {
		return $this->_( 'transport', 'refresh' );
	}


	/**
	 * Set transport property to 'postMessage'
	 *
	 * @return array|Field_Options_Location_Customize|mixed|null
	 */
	public function transport_postMessage() {
		return $this->_( 'transport', 'postMessage' );
	}

	//validate_callback(callable)
	//Server-side validation callback for the setting's value.

	//sanitize_callback(callable)
	//Callback to filter a Customize setting value in un-slashed form.

	//sanitize_js_callback(callable)
	//Callback to convert a Customize PHP setting value to a value that is JSON serializable.

	//dirty(true/false)
	//Whether or not the setting is initially dirty when created.

}