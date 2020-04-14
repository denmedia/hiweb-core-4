<?php

	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Color\Field_Color;


	/**
	 * @param $field_ID
	 * @return Field_Options
	 */
	function add_field_color( $field_ID ){
		return FieldsFactory::add_field( new Field_Color( $field_ID ) );
	}