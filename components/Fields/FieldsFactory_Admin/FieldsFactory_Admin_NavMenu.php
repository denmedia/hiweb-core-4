<?php
	
	namespace hiweb\components\Fields\FieldsFactory_Admin;
	
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\FieldsFactory_Admin;
	use hiweb\components\NavMenus\NavMenusFactory;
	
	
	class FieldsFactory_Admin_NavMenu{
		
		
		/**
		 * Print Field in menu editor
		 * @param $item_id
		 * @param $item
		 * @param $depth
		 * @param $args
		 * @param $id
		 */
		static function wp_nav_menu_item_custom_fields( $item_id, $item, $depth, $args, $id ){
			$fields_query = [ 'nav_menu' => [ 'ID' => $item->ID, 'depth' => $depth, 'locations' => NavMenusFactory::get_by_menu_item( $item_id )->get_locations() ] ];
			echo FieldsFactory_Admin::get_ajax_form_html( $fields_query, [ 'name_before' => 'hiweb-nav_menu-', 'name_after' => '[' . $item_id . ']' ] );
		}
		
		
		/**
		 * Save menu item meta
		 * @param $menu_id
		 * @param $menu_item_db_id
		 * @param $args
		 */
		static function wp_update_nav_menu_item( $menu_id, $menu_item_db_id, $args ){
			if( !array_key_exists( 'hiweb-core-field-form-nonce', $_POST ) || !wp_verify_nonce( $_POST['hiweb-core-field-form-nonce'], 'hiweb-core-field-form-save' ) ) return;
			$fields = FieldsFactory::get_fields_by_query( [ 'nav_menu' => [] ] );
			foreach( $fields as $Field ){
				$field_name = 'hiweb-nav_menu-' . $Field->id();
				if( $Field->get_allow_save_field( array_key_exists( $field_name, $_POST ) ? $_POST[ $field_name ] : null ) ){
					if( array_key_exists( $field_name, $_POST ) && array_key_exists( $menu_item_db_id, $_POST[ $field_name ] ) ){
						update_post_meta( $menu_item_db_id, $Field->id(), $Field->get_sanitize_admin_value( $_POST[ $field_name ][ $menu_item_db_id ], true ) );
					}
					else{
						update_post_meta( $menu_item_db_id, $Field->id(), $Field->get_sanitize_admin_value( '', true ) );
					}
				}
			}
		}
		
	}