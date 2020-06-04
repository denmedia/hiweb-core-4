<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-14
	 * Time: 19:25
	 */

	namespace theme;


	use theme\structures\structure;


	class structures{

		/** @var structure[] */
		static private $structures = [];


		/**
		 * @param $object
		 * @return string
		 */
		static function object_to_id( $object ){
			if( $object instanceof \WP_Post_Type ){
				return 'post_type_archive:' . $object->name;
			} elseif( $object instanceof \WP_Post ) {
				return 'post_type:' . $object->ID;
			} elseif( $object instanceof \WP_Term ) {
				return 'taxonomy:' . $object->term_id;
			} elseif( is_null( $object ) ) {
				global $wp_query;
				if( $wp_query instanceof \WP_Query && $wp_query->is_search() ){
					return 'search';
				}
				if( $wp_query instanceof \WP_Query && $wp_query->is_404() ){
					return '404';
				}
			}
			return '';
		}


		/**
		 * @param null $wp_object
		 * @return structure
		 */
		static function get( $wp_object = null ){
			if( !is_object( $wp_object ) && function_exists( 'get_queried_object' ) ){
				$wp_object = get_queried_object();
			}
			$object_id = self::object_to_id( $wp_object );
			if( !array_key_exists( $object_id, self::$structures ) ){
				self::$structures[ $object_id ] = new structure( $wp_object );
			}
			return self::$structures[ $object_id ];
		}


		/**
		 * @param $nav_menu_item
		 * @return array|bool|\WP_Error|\WP_Post|\WP_Post_Type|\WP_Term|null
		 */
		static function wp_post_nav_to_wp_object( $nav_menu_item ){
			if( $nav_menu_item instanceof \WP_Post && $nav_menu_item->post_type == 'nav_menu_item' ){
				switch( $nav_menu_item->type ){
					case 'post_type_archive':
						$nav_menu_item = get_post_type_object( $nav_menu_item->object );
						break;
					case 'post_type':
						$nav_menu_item = get_post( $nav_menu_item->object_id );
						break;
					case 'taxonomy':
						$nav_menu_item = get_term( $nav_menu_item->object_id );
						break;
					case 'custom':
						//do nothing
						break;
					default:
						$nav_menu_item = false;
						break;
				}
			}
			return $nav_menu_item;
		}


	}