<?php
	
	if( !function_exists( 'add_admin_menu_page' ) ){
		
		/**
		 * @param      $slug
		 * @param      $title
		 * @param null $parent_slug
		 * @return \hiweb\components\AdminMenu\AdminMenu_Page
		 */
		function add_admin_menu_page( $slug, $title, $parent_slug = null ){
			return \hiweb\components\AdminMenu\AdminMenuFactory::add( $slug, $title, $parent_slug );
		}
	}