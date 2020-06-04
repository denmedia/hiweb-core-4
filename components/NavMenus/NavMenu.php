<?php

	namespace hiweb\components\NavMenus;


	use hiweb\components\Preloader\PreloaderFactory;
	use hiweb\core\Cache\CacheFactory;
	use WP_Post;


	class NavMenu{


		private $id;


		public function __construct( $nav_menu_id ){
			$this->id = intval( $nav_menu_id );
		}


		/**
		 * @return array|WP_Post[]
		 */
		public function get_items(){
			return CacheFactory::get( $this->id, __CLASS__ . '::$items', function(){
				if( $this->id == 0 ){
					return [];
				} else {
					$R = wp_get_nav_menu_items( $this->id );
					return is_array( $R ) ? $R : [];
				}
			} )->get_value();
		}


		/**
		 * @return array
		 */
		public function get_associated_objects(){
			return CacheFactory::get( $this->id, __CLASS__ . '::$associated_objects', function(){
				$R = [];
				foreach( self::get_items() as $nav_menu_item ){
					$R[ $nav_menu_item->ID . ':' . hiweb_nav_menu_item_to_wp_object_id( $nav_menu_item ) ] = hiweb_nav_menu_item_to_wp_object( $nav_menu_item );
				}
				return $R;
			} )->get_value();
		}

	}