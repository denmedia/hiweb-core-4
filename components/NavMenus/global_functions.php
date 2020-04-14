<?php

	use hiweb\components\NavMenus\NavMenu;
	use hiweb\components\NavMenus\NavMenusFactory;


	if( !function_exists( 'get_nav_menu_by_id' ) ){
		/**
		 * @param int $nav_menu_id
		 * @return NavMenu
		 */
		function get_nav_menu_by_id( $nav_menu_id ){
			return NavMenusFactory::get( $nav_menu_id );
		}
	}

	if( !function_exists( 'get_nav_menu_by_location' ) ){
		/**
		 * @param string $location
		 * @return NavMenu
		 */
		function get_nav_menu_by_location( $location ){
			return NavMenusFactory::get_by_location( $location );
		}
	}

	if( !function_exists( 'get_nav_menu_by_name' ) ){
		/**
		 * @param string $nav_menu_name
		 * @return NavMenu
		 */
		function get_nav_menu_by_name( $nav_menu_name ){
			return NavMenusFactory::get_by_name( $nav_menu_name );
		}
	}
