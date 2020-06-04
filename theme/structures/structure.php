<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-14
	 * Time: 19:38
	 */
	
	namespace theme\structures;
	
	
	use hiweb\components\Structures\StructuresFactory;
	use hiweb\core\Paths\PathsFactory;
	use theme\seo;
	use theme\structures;
	use WooCommerce;
	use WP_Error;
	use WP_Post;
	use WP_Post_Type;
	use WP_Query;
	use WP_Taxonomy;
	use WP_Term;
	
	
	class structure{
		
		public $wp_object;
		public $is_search;
		public $id;
		
		private $cache_parent_post_types;
		private $cache_parent_blog_page;
		private $cache_parent_terms;
		private $cache_parent_by_nav_menu;
		private $cache_parent_objects;
		private $cache_parents;
		private $cache_parent_urls;
		
		
		public function __construct( $object ){
			$this->wp_object = $object;
			if( is_null( $this->wp_object ) ){
				global $wp_query;
				if( $wp_query instanceof WP_Query && $wp_query->is_search ){
					$this->is_search = true;
				}
			}
			$this->id = structures::object_to_id( $object );
		}
		
		
		/**
		 * @return string
		 */
		public function get_id(){
			return $this->id;
		}
		
		
		/**
		 * @return bool|false|string|WP_Error
		 */
		public function get_url(){
			if( $this->is_search ){
				return get_home_url() . '?s=' . PathsFactory::request( 's' );
			}
			elseif( $this->wp_object instanceof WP_Post ){
				return get_permalink( $this->wp_object );
			}
			elseif( $this->wp_object instanceof WP_Term ){
				return get_term_link( $this->wp_object );
			}
			elseif( $this->wp_object instanceof WP_Post_Type && $this->wp_object->public && $this->wp_object->publicly_queryable && $this->wp_object->has_archive ){
				return get_post_type_archive_link( $this->wp_object->name );
			}
			return get_home_url();
		}
		
		
		/**
		 * @return bool
		 */
		public function is_exists(){
			return strpos( $this->id, ':' ) !== 0;
		}
		
		
		/**
		 * @param bool $force_raw
		 * @return mixed|string
		 */
		public function get_title( $force_raw = true ){
			if( $this->is_search ){
				return apply_filters( '\theme\structures\structure::get_title', 'Результаты поиска', $this->wp_object, $force_raw, $this );
			}
			elseif( $this->wp_object instanceof WP_Post ){
				return apply_filters( '\theme\structures\structure::get_title', $force_raw ? $this->wp_object->post_title : get_the_title( $this->wp_object ), $this->wp_object, $force_raw, $this );
			}
			elseif( $this->wp_object instanceof WP_Term ){
				return apply_filters( '\theme\structures\structure::get_title', $this->wp_object->name, $this->wp_object, $force_raw, $this );
			}
			elseif( $this->wp_object instanceof WP_Post_Type ){
				if( $this->wp_object->name == 'product' && function_exists( 'WC' ) ){
					$shop_page_id = get_option( 'woocommerce_shop_page_id' );
					if( get_post( $shop_page_id ) instanceof WP_Post && get_post( $shop_page_id )->post_type == 'page' ){
						$title = get_the_title( $shop_page_id );
						if( $title != '' ) return $title;
					}
				}
				if( class_exists( '\theme\seo' ) ){
					$title = seo::get_post_type_title( $this->wp_object->name );
					if( $title != '' ) return $title;
				}
				return apply_filters( '\theme\structures\structure::get_title', $this->wp_object->label, $this->wp_object, $force_raw, $this );
			}
			else{
				return get_bloginfo( 'name' );
			}
		}
		
		
		/**
		 * @return array|WP_Post[]
		 */
		public function get_parent_wp_post(){
			if( $this->wp_object instanceof WP_Post ){
				if( $this->wp_object->post_parent != 0 ){
					$wp_post_test = get_post( $this->wp_object->post_parent );
					if( $wp_post_test instanceof WP_Post && $this->wp_object != $wp_post_test ) return [ $wp_post_test ];
				}
			}
			return [];
		}
		
		
		/**
		 * @return array|WP_Term[]
		 */
		public function get_parent_wp_term(){
			if( !is_array( $this->cache_parent_terms ) ){
				$this->cache_parent_terms = [];
				if( $this->wp_object instanceof WP_Post ){
					$taxonomies = get_object_taxonomies( $this->wp_object->post_type );
					foreach( $taxonomies as $taxonomy ){
						if( !get_taxonomy( $taxonomy )->public ) continue;
						$terms = get_the_terms( $this->wp_object, $taxonomy );
						if( is_array( $terms ) ) $this->cache_parent_terms = array_merge( $this->cache_parent_terms, $terms );
					}
				}
				elseif( $this->wp_object instanceof WP_Term && $this->wp_object->parent != 0 ){
					$wp_term_test = get_term( $this->wp_object->parent );
					if( $wp_term_test instanceof WP_Term && $this->wp_object != $wp_term_test ){
						$this->cache_parent_terms = [ $wp_term_test ];
					}
				}
			}
			return $this->cache_parent_terms;
		}
		
		
		/**
		 * @return array
		 */
		public function get_parent_blog_page(){
			if( !is_array( $this->cache_parent_blog_page ) ){
				$this->cache_parent_blog_page = [];
				if( StructuresFactory::get_blog_page() instanceof WP_Post && StructuresFactory::get_blog_id() != 0 && $this->wp_object != StructuresFactory::get_blog_page() ){
					if( $this->wp_object instanceof WP_Post ){
						if( $this->wp_object->post_type == 'post' ){
							$this->cache_parent_blog_page[] = StructuresFactory::get_blog_page();
						}
					}
					elseif( $this->wp_object instanceof WP_Term ){
						$taxonomy = get_taxonomy( $this->wp_object->taxonomy );
						if( $taxonomy instanceof WP_Taxonomy ){
							foreach( $taxonomy->object_type as $post_type ){
								if( $post_type == 'post' ){
									$this->cache_parent_blog_page[] = StructuresFactory::get_blog_page();
									break;
								}
							}
						}
					}
				}
			}
			return $this->cache_parent_blog_page;
		}
		
		
		/**
		 * @return WP_Post_Type[]
		 */
		public function get_parent_wp_post_type(){
			if( !is_array( $this->cache_parent_post_types ) ){
				$this->cache_parent_post_types = [];
				if( $this->wp_object instanceof WP_Post ){
					$post_type_object = get_post_type_object( $this->wp_object->post_type );
					if( $post_type_object->public && $post_type_object->has_archive ){
						$this->cache_parent_post_types[ $this->wp_object->post_type ] = $post_type_object;
					}
				}
				elseif( $this->wp_object instanceof WP_Term ){
					$taxonomy = get_taxonomy( $this->wp_object->taxonomy );
					if( $taxonomy instanceof WP_Taxonomy ){
						foreach( $taxonomy->object_type as $post_type ){
							$post_type_object = get_post_type_object( $post_type );
							if( $post_type_object->public && $post_type_object->has_archive ){
								$this->cache_parent_post_types[ $post_type ] = $post_type_object;
							}
						}
					}
				}
			}
			return $this->cache_parent_post_types;
		}
		
		
		/**
		 * @return WP_Post[]
		 */
		public function get_parent_woocommerce_shop_page(){
			$R = [];
			if( function_exists( 'WC' ) && WC() instanceof WooCommerce ){
				if( ( $this->wp_object instanceof WP_Post && in_array( $this->wp_object->post_type, apply_filters( 'rest_api_allowed_post_types', [] ) ) ) ){
					$wp_post_test = get_post( wc_get_page_id( 'shop' ) );
					if( $wp_post_test instanceof WP_Post && $wp_post_test != $this->wp_object ){
						$R[ $wp_post_test->ID ] = $wp_post_test;
					}
				}
				elseif( $this->wp_object instanceof WP_Term ){
					$taxonomy = get_taxonomy( $this->wp_object->taxonomy );
					foreach( $taxonomy->object_type as $post_type ){
						$post_type_object = get_post_type_object( $post_type );
						if( $post_type_object->public && in_array( $post_type, apply_filters( 'rest_api_allowed_post_types', [] ) ) ){
							$wp_post_test = get_post( wc_get_page_id( 'shop' ) );
							if( $wp_post_test instanceof WP_Post && $wp_post_test != $this->wp_object ){
								$R[ $wp_post_test->ID ] = $wp_post_test;
							}
						}
					}
				}
			}
			return $R;
		}
		
		
		/**
		 * @return array
		 */
		public function get_parent_wp_object_by_nav(){
			if( !is_array( $this->cache_parent_by_nav_menu ) ){
				$this->cache_parent_by_nav_menu = [];
				///
				foreach( get_nav_menu_locations() as $location ){
					/** @var WP_Post $wp_nav_item */
					$nav_items = [];
					foreach( wp_get_nav_menu_items( $location ) as $nav_menu_item ){
						$nav_items[ $nav_menu_item->ID ] = $nav_menu_item;
					}
					
					foreach( $nav_items as $wp_nav_item ){
						if( rtrim( $wp_nav_item->url, '/' ) == rtrim( $this->get_url(), '/' ) && $wp_nav_item->menu_item_parent != 0 ){
							$parent_menu_nav_item = $nav_items[ $wp_nav_item->menu_item_parent ];
							$object = structures::wp_post_nav_to_wp_object( $parent_menu_nav_item );
							if( is_object( $object ) && $this->wp_object != $object ){
								if( get_post_type_object( $object->post_type ) instanceof \WP_Post_Type && get_post_type_object( $object->post_type )->public ){
									$this->cache_parent_by_nav_menu[ structures::object_to_id( $object ) ] = $object;
								}
							}
						}
					}
				}
			}
			///
			return $this->cache_parent_by_nav_menu;
		}
		
		
		/**
		 * @return array|WP_Post[]|WP_Post_Type[]|WP_Term[]
		 */
		public function get_parent_wp_objects(){
			if( !is_array( $this->cache_parent_objects ) ){
				$this->cache_parent_objects = [];
				if( $this->wp_object instanceof WP_Post ){
					if( count( $this->get_parent_wp_post() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_wp_post();
					}
					elseif( count( $this->get_parent_wp_term() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_wp_term();
					}
					elseif( count( $this->get_parent_wp_object_by_nav() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_wp_object_by_nav();
					}
					elseif( count( $this->get_parent_blog_page() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_blog_page();
					}
					elseif( count( $this->get_parent_wp_post_type() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_wp_post_type();
					}
					elseif( count( $this->get_parent_woocommerce_shop_page() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_woocommerce_shop_page();
					}
					else{
						$this->cache_parent_objects = [];
					}
				}
				elseif( $this->wp_object instanceof WP_Term ){
					if( count( $this->get_parent_wp_term() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_wp_term();
					}
					elseif( count( $this->get_parent_wp_object_by_nav() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_wp_object_by_nav();
					}
					elseif( count( $this->get_parent_blog_page() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_blog_page();
					}
					elseif( count( $this->get_parent_wp_post_type() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_wp_post_type();
					}
					elseif( count( $this->get_parent_woocommerce_shop_page() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_woocommerce_shop_page();
					}
					else{
						$this->cache_parent_objects = [];
					}
				}
				elseif( $this->wp_object instanceof WP_Post_Type ){
					if( count( $this->get_parent_wp_object_by_nav() ) > 0 ){
						$this->cache_parent_objects = $this->get_parent_wp_object_by_nav();
					}
					else{
						$this->cache_parent_objects = [];
					}
				}
			}
			return $this->cache_parent_objects;
		}
		
		
		/**
		 * @param bool $return_current - возвращать вместе с текущей структурой в массиве
		 * @return structure[]
		 */
		public function get_parents( $return_current = true ){
			if( !is_array( $this->cache_parents ) ){
				$this->cache_parents = $return_current ? [ $this->get_id() => $this ] : [];
				$parent_objects = $this->get_parent_wp_objects();
				if( is_array( $parent_objects ) && count( $parent_objects ) > 0 ){
					$parent_structure = structures::get( reset( $parent_objects ) );
					$this->cache_parents = array_merge( $this->cache_parents, [ $parent_structure->get_id() => $parent_structure ], $parent_structure->get_parents() );
				}
			}
			return $this->cache_parents;
		}
		
		
		/**
		 * @return array
		 */
		public function get_parent_urls(){
			if( !is_array( $this->cache_parent_urls ) ){
				$this->cache_parent_urls = [];
				foreach( $this->get_parents() as $structure ){
					$this->cache_parent_urls[ $structure->get_url() ] = $structure->get_title();
				}
			}
			
			return $this->cache_parent_urls;
		}
		
		
		/**
		 * @param $url
		 * @return bool
		 */
		public function has_url( $url ){
			return array_key_exists( $url, $this->get_parent_urls() );
		}
		
		
		/**
		 * @param WP_Post|WP_Term|WP_Post_Type|string $wp_object_or_objectId - можно сразу указать ID объекта в виде строки
		 * @return bool
		 */
		public function has_object( $wp_object_or_objectId ){
			if( is_string( $wp_object_or_objectId ) ){
				$wp_object_id = $wp_object_or_objectId;
			}
			else{
				$wp_object_id = structures::object_to_id( structures::wp_post_nav_to_wp_object( $wp_object_or_objectId ) );
			}
			return $wp_object_id != '' && array_key_exists( $wp_object_id, $this->get_parents() );
		}
		
	}