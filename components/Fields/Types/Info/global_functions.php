<?php
	
	if( !function_exists( 'add_field_info' ) ){
		function add_field_info( $info ){
			return \hiweb\components\Fields\FieldsFactory::add_field( new \hiweb\components\Fields\Types\Info\Field_Info() )->default_value( $info );
		}
	}