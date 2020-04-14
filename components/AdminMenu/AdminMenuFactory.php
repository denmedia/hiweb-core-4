<?php

	namespace hiweb\components\AdminMenu;


	use hiweb\components\Console\ConsoleFactory;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;


	class AdminMenuFactory{


		use hidden_methods;


		/**
		 * @param      $slug
		 * @param      $title
		 * @param null $parent_slug
		 * @return AdminMenu_Page
		 */
		static function add( $slug, $title = 'Опции', $parent_slug = null ){
			return CacheFactory::get( $slug, __CLASS__ . '::$admin_menus', function(){
				if( !is_null( func_get_arg( 2 ) ) ){
					$Page = new AdminMenu_Page( func_get_arg( 0 ) );
					$Page->parent_slug( func_get_arg( 2 ) );
				} else {
					$Page = new AdminMenu_Page( func_get_arg( 0 ) );
					$Page->icon_url( 'fad fa-sliders-v-square' );
				}
				$Page->page_title( func_get_arg( 1 ) );
				$Page->menu_title( func_get_arg( 1 ) );
				$Page->capability( 'edit_theme_options' );
				$Page->position( 81 );
				return $Page;
			}, [ $slug, $title, $parent_slug ] )->get_value();
		}


		static private function _register_admin_menus(){
			foreach( CacheFactory::get_group( __CLASS__ . '::$admin_menus', true ) as $slug => $AdminMenu_Page ){
				/** @var AdminMenu_Page $AdminMenu_Page */
				if( $AdminMenu_Page->parent_slug() == '' ){
					add_menu_page( $AdminMenu_Page->page_title(), $AdminMenu_Page->menu_title(), $AdminMenu_Page->capability(), $AdminMenu_Page->menu_slug(), function(){ echo 'WORK!!!'; }, $AdminMenu_Page->icon_url(), $AdminMenu_Page->position() );
				} else {
					add_submenu_page( $AdminMenu_Page->parent_slug(), $AdminMenu_Page->page_title(), $AdminMenu_Page->menu_title(), $AdminMenu_Page->capability(), $AdminMenu_Page->menu_slug(), function(){ echo 'WORK!!!'; }, $AdminMenu_Page->position() );
				}
			}
		}

	}