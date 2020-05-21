<?php
	
	namespace hiweb\components\Structures;
	
	
	use hiweb\components\Console\ConsoleFactory;
	use hiweb\core\Cache\CacheFactory;
	use WP_Post;
	use WP_Post_Type;
	use WP_Query;
	use WP_Term;
	use WP_User;
	
	
	/**
	 * Class StructuresFactory
	 * @version 1.1
	 * @package hiweb\components\Structures
	 */
	class StructuresFactory{
		
		static $options_priority = [
			'post_type' => [
				'post_parent',
				'terms',
				'nav_menus',
				'blog_page',
				'post_type_archive',
				'woocommerce_shop_page'
			],
			'taxonomy' => [
				'terms',
				'nav_menus',
				'blog_page',
				'post_type_archive',
				'woocommerce_shop_page'
			],
			'post_type_archive' => [
				'nav_menus'
			]
		];
		
		
		/**
		 * @param null $wp_object
		 * @return Structure
		 */
		static function get( $wp_object = null ){
			if( !is_object( $wp_object ) && function_exists( 'get_queried_object' ) ) $wp_object = get_queried_object();
			$object_id = self::get_id_from_object( $wp_object );
			return CacheFactory::get( $object_id, __METHOD__, function(){
				return new Structure( func_get_arg( 0 ), func_get_arg( 1 ) );
			}, [ $wp_object, $object_id ] )->get_value();
		}
		
		
		/**
		 * @return mixed|void
		 */
		static function get_front_page_id(){
			return intval( get_option( 'page_on_front' ) );
		}
		
		
		/**
		 * Return front page WP_Pos or null iof not exists
		 * @return null|WP_Post
		 */
		static function get_front_page(){
			return CacheFactory::get( __FUNCTION__, __CLASS__, function(){
				$test_page = get_post( self::get_front_page_id() );
				if( $test_page instanceof WP_Post ) return $test_page;
				else return null;
			} )->get_value();
		}
		
		
		/**
		 * @return mixed|void
		 */
		static function get_blog_id(){
			return intval( get_option( 'page_for_posts' ) );
		}
		
		
		/**
		 * Return blog WP_post or null if not exists
		 * @return null|WP_Post
		 */
		static function get_blog_page(){
			return CacheFactory::get( __FUNCTION__, __CLASS__, function(){
				$test_page = get_post( self::get_blog_id() );
				if( $test_page instanceof WP_Post ) return $test_page;
				else return null;
			} )->get_value();
		}
		
		
		/**
		 * Return p
		 * @return int
		 */
		static function get_privacy_policy_id(){
			return (int)get_option( 'wp_page_for_privacy_policy' );
		}
		
		
		/**
		 * Return privacy policy page WP_post or null if not exists
		 * @return null|WP_Post
		 * @return null
		 */
		static function get_page_for_privacy_policy(){
			return CacheFactory::get( __FUNCTION__, __CLASS__, function(){
				$test_page = get_post( self::get_privacy_policy_id() );
				if( $test_page instanceof WP_Post ) return $test_page;
				else return null;
			} )->get_value();
		}
		
		
		/**
		 * Convert wp object (like WP_Post / WP_Term) to string id
		 * @param $wp_object
		 * @return string
		 */
		static function get_id_from_object( $wp_object ){
			if( is_string( $wp_object ) ){
				return $wp_object;
			}
			elseif( $wp_object instanceof WP_Post_Type ){
				return 'post_type_archive:' . $wp_object->name;
			}
			elseif( $wp_object instanceof WP_Post ){
				return 'post_type:' . $wp_object->ID;
			}
			elseif( $wp_object instanceof WP_Term ){
				return 'taxonomy:' . $wp_object->term_id;
			}
			elseif( $wp_object instanceof WP_User ){
				return 'user:' . $wp_object->ID;
			}
			elseif( is_null( $wp_object ) ){
				global $wp_query;
				if( $wp_query instanceof WP_Query && $wp_query->is_front_page() ){
					return 'front-page';
				}
				if( $wp_query instanceof WP_Query && $wp_query->is_home() ){
					return 'home';
				}
				if( $wp_query instanceof WP_Query && $wp_query->is_search() ){
					return 'search';
				}
				if( $wp_query instanceof WP_Query && $wp_query->is_404() ){
					return '404';
				}
			}
			return '';
		}
		
		
		static function get_object_from_id( $object_id = 'post_type:1' ){
			if( preg_match( '/^[\w_]+:[\d\w]+$/i', $object_id ) == 0 ){
				ConsoleFactory::add( 'Unknown object id (1)', 'warn', __FUNCTION__, $object_id, true );
				return null;
			}
			list( $object_type, $object_id ) = explode( ':', $object_id );
			$R = null;
			switch( $object_type ){
				case 'post_type_archive':
					return get_post_type_object( $object_id );
					break;
				case 'post_type':
					return get_post( $object_id );
					break;
				case 'taxonomy':
					return get_term( $object_id );
					break;
				case 'custom':
					//do nothing
					break;
				default:
					ConsoleFactory::add( 'Unknown object id (2)', 'warn', __FUNCTION__, $object_id, true );
					break;
			}
			return $R;
		}
		
	}