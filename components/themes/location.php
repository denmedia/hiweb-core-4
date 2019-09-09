<?php

	namespace hiweb\themes;


	class location{

		public $name = '';
		public $id = 0;
		public $slug = '';
		public $menus = [];
		private $location;


		public function __construct( $location ){
			$this->location = $location;
			$locations = get_registered_nav_menus();
			$menus = wp_get_nav_menus();
			if( array_key_exists( $location, $locations ) ){
				$this->slug = $location;
				$this->name = $locations[ $location ];
				//$location_ids = hiweb()->theme()->locations(); //TODO!
				foreach( $menus as $menu ){
					if( $menu->term_id == $location_ids[ $location ] ){
						$this->menus[] = $menu;
					}
				}
			}
		}

	}