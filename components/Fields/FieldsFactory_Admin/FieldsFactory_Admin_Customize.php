<?php

namespace hiweb\components\Fields\FieldsFactory_Admin;


use hiweb\components\Fields\FieldsFactory;
use hiweb\core\Strings;
use WP_Customize_Manager;


/**
 * Class for print fields in the theme customize page
 *
 * @package hiweb\components\Fields\FieldsFactory_Admin
 */
class FieldsFactory_Admin_Customize {

	/**
	 * @param WP_Customize_Manager $wp_customize
	 */
	static function customize_register(WP_Customize_Manager $wp_customize) {
		$fields = FieldsFactory::get_fields_by_query( [ 'customize' => [] ] );
		$sections = [];
		foreach ( $fields as $field ) {
			///add settings by field
			$customize_array = get_array( $field->options()->location()->_get_optionsCollect() );
			$args = array();
			foreach ( [ 'type', 'transport', 'capability', 'theme_supports' ] as $key_name ) {
				if ( $customize_array->is_key_exists( $key_name ) )
					$args[$key_name] = $customize_array( $key_name );
			}
			if ( !empty( $field->options()->default_value() ) )
				$args['default'] = $field->options()->default_value();
			$wp_customize->add_setting( $field->id(), $args );
			///create section if not exists
			$section_id = Strings::sanitize_id( $field->options()->location()->customize()->section()->title() );
			if ( !array_key_exists( $section_id, $sections ) ) {
				$sections[] = $section_id;
				$wp_customize->add_section( $section_id, $field->options()->location()->customize()->section()->_get_optionsCollect() );
			}
			$wp_customize->add_control( new FieldsFactory_Admin_Customize_Control( $field, $wp_customize, $section_id ) );
		}
	}

}