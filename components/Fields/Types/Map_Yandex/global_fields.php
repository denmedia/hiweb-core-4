<?php
	
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Map_Yandex\Field_MapYandex;
	
	
	if( !function_exists( 'add_field_map_yandex' ) ){
		/**
		 * @param $field_ID
		 * @return Field_Options
		 */
		function add_field_map_yandex( $field_ID ){
			return FieldsFactory::add_field( new Field_MapYandex( $field_ID ) );
		}
	}