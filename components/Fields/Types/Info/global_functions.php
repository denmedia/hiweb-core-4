<?php
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Info\Field_Info;
	
	
	if( !function_exists( 'add_field_info' ) ){
		function add_field_info( $info ){
			return FieldsFactory::add_field( new Field_Info() )->default_value( $info );
		}
	}