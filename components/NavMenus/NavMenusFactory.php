<?php
	
	namespace hiweb\components\NavMenus;
	
	
	use hiweb\components\Structures\StructuresFactory;
	use hiweb\core\Cache\CacheFactory;
	use WP_Error;
	use WP_Post;
	use WP_Post_Type;
	use WP_Term;
	
	
	class NavMenusFactory{
		
		/**
		 * @param $nav_menu_id
		 * @return NavMenu
		 */
		static function get( $nav_menu_id ){
			$nav_menu_id = intval( $nav_menu_id );
			return CacheFactory::get( $nav_menu_id, __CLASS__ . '::$menus', function(){
				return new NavMenu( func_get_arg( 0 ) );
			}, [ $nav_menu_id ] )->get_value();
		}
		
		
		/**
		 * @param string $location
		 * @return NavMenu
		 */
		static function get_by_location( $location ){
			return CacheFactory::get( $location, __CLASS__ . '::$locations', function(){
				if( is_array( get_theme_mod( 'nav_menu_locations' ) ) && array_key_exists( func_get_arg( 0 ), get_theme_mod( 'nav_menu_locations' ) ) ){
					$term_id = get_theme_mod( 'nav_menu_locations' )[ func_get_arg( 0 ) ];
					return NavMenusFactory::get( $term_id );
				}
				else{
					return NavMenusFactory::get( 0 );
				}
			}, $location )->get_value();
		}
		
		
		/**
		 * Return NavMenu by menu item id
		 * @param WP_Post|int|number $menuPostOrId
		 * @return NavMenu
		 */
		static function get_by_menu_item( $menuPostOrId ){
			$R = 0;
			$terms = get_the_terms( $menuPostOrId, 'nav_menu' );
			if( is_array( $terms ) && count( $terms ) > 0 ){
				$tmp_term = reset( $terms );
				if( $tmp_term instanceof WP_Term ){
					$R = $tmp_term->term_id;
				}
			}
			return self::get( $R );
		}
		
		
		/**
		 * @param string $menu_name
		 * @return NavMenu
		 */
		static function get_by_name( $menu_name = 'Меню сайта' ){
			$terms = CacheFactory::get( $menu_name, __CLASS__ . '::$terms_by_name', function(){
				return wp_get_nav_menus( [
					'name' => func_get_arg( 0 ),
					'taxonomy' => 'nav_menu',
					'fields' => 'ids'
				] );
			}, [ $menu_name ] )->get_value();
			return self::get( reset( $terms ) );
		}
		
		
		/**
		 * Convert nav_menu_item to wp object id
		 * @param WP_Post $nav_menu_item
		 * @return null|string
		 */
		static function get_id_from_object( $nav_menu_item ){
			if( $nav_menu_item instanceof WP_Post && $nav_menu_item->post_type == 'nav_menu_item' ){
				return $nav_menu_item->type . ':' . $nav_menu_item->object_id;
			}
			return null;
		}
		
		
		/**
		 * Convert nav_menu_item to wp object
		 * @param WP_Post $nav_menu_item
		 * @return array|bool|WP_Error|WP_Post|WP_Post_Type|WP_Term|null
		 */
		static function get_wp_object_from_nav_menu_post( $nav_menu_item ){
			return StructuresFactory::get_object_from_id( self::get_id_from_object( $nav_menu_item ) );
		}
		
		
		/**
		 * @param bool $hide_empty
		 * @return WP_Term[]
		 */
		static function get_menu_nav_terms( $hide_empty = true ){
			$R = get_terms( [ 'taxonomy' => 'nav_menu', 'hide_empty' => $hide_empty ] );
			if( !is_array( $R ) ) $R = [];
			return $R;
		}
		
	}