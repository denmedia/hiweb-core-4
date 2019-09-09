<?php

	namespace hiweb\admin\pages;


	use hiweb\console;


	class pages{

		/** @var page[] */
		static $pages = [];
		/** @var subpage[] */
		static $subpages = [];


		/**
		 * Hook action, add admin menu pages in WP
		 * @return mixed
		 */
		static function _hook_admin_menu(){
			$R = [];
			if( is_array( self::$pages ) ){
				foreach( self::$pages as $slug => $page ){
					if( $page instanceof page ){
						$R[ $slug ] = add_menu_page( $page->page_title(), $page->menu_title(), $page->capability(), $page->menu_slug(), [ $page, 'the_form' ], $page->icon_url(), $page->position() );
					} elseif( $page instanceof subpage ) {
						$R[ $slug ] = add_submenu_page( $page->parent_slug(), $page->page_title(), $page->menu_title(), $page->capability(), $page->menu_slug(), [ $page, 'the_form' ] );
					} else {
						console::debug_error( 'В массиве pages::$pages попался не экземпляр page_abstract', [ $slug, get_class( $page ) ] );
						$R[ $slug ] = false;
					}
				}
			} else {
				console::debug_error( 'pages::$pages не массив!', self::$pages );
			}
			return $R;
		}

	}