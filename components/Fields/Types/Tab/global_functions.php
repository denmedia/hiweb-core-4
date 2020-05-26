<?php
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\Types\Tab\Field_Tab;
	use hiweb\components\Fields\Types\Tab\Field_Tab_Options;
	
	
	if( !function_exists( 'add_field_tab' ) ){
		
		/**
		 * @param string $tab_label
		 * @param string $tab_description
		 * @return Field_Tab_Options|mixed
		 */
		function add_field_tab( $tab_label = '', $tab_description = '' ){
			$new_field_tab = FieldsFactory::add_field( new Field_Tab() );
			$new_field_tab->label( $tab_label );
			$new_field_tab->description( $tab_description );
			return $new_field_tab;
		}
	}

	if(!function_exists('add_field_tab_end')){
		function add_field_tab_end(){
			$new_field_tab = FieldsFactory::add_field( new Field_Tab() );
			$new_field_tab->location(true);
			$new_field_tab->label( '' );
		}
	}