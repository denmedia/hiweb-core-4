<?php

	namespace hiweb\themes;


	class theme{

		/** @var  string */
		private $theme_slug;
		/** @var  \WP_Theme */
		private $wp_theme;

		/** @var location[] */
		private $locations = [];


		public function __construct( $theme ){
			$this->theme_slug = trim( $theme ) == '' ? get_option( 'stylesheet' ) : $theme;
			$this->wp_theme = wp_get_theme( $this->theme_slug );
		}


		/**
		 * @return \WP_Theme
		 */
		public function wp_theme(){
			return $this->wp_theme;
		}


		public function exist(){
			//todo
		}


		/**
		 * Возвращает массив локаций
		 * @return array
		 */
		public function locations(){
			$R = [];
			$mods = get_option( 'theme_mods_' . $this->theme_slug );
			if( isset( $mods['nav_menu_locations'] ) ){
				$R = $mods['nav_menu_locations'];
			}

			return $R;
		}


		/**
		 * @param $location
		 * @return location
		 */
		public function location( $location = null ){
			if( !array_key_exists( $location, $this->locations ) ){
				$this->locations[ $location ] = new location( $location );
			}

			return $this->locations[ $location ];
		}


		/**
		 * Возвращает массив с массивами элементов
		 * @param $location
		 * @return array|false
		 */
		public function menu_items( $location ){
			$R = [];
			$menus = wp_get_nav_menus();
			$menu_locations = $this->locations();
			if( isset( $menu_locations[ $location ] ) ){
				foreach( $menus as $menu ){
					if( $menu->term_id == $menu_locations[ $location ] ){
						$items = wp_get_nav_menu_items( $menu );
						return $items;
					}
				}
			}

			return $R;
		}


		/**
		 * @return string
		 */
		public function dir(){
			return dirname( get_template_directory() ) . '/' . $this->theme_slug;
		}


		/**
		 * @return string
		 */
		public function url(){
			return dirname( get_template_directory_uri() ) . '/' . $this->theme_slug;
		}


		/**
		 * Return post (or posts array) by template file name, like 'page-template.php', or FALSE, if them not exists
		 * @param string $template_name
		 * @param bool   $return_array
		 * @return bool|\WP_Post|\WP_Post[]
		 */
		public function get_post_by_template( $template_name = 'page-template.php', $return_array = false ){
			$args = [
				'post_type' => 'page',
				'nopaging' => true,
				'meta_key' => '_wp_page_template',
				'meta_value' => $template_name
			];
			$pages = get_posts( $args );
			if( !is_array( $pages ) || count( $pages ) == 0 ) return false;
			return $return_array ? $pages : reset( $pages );
		}


		/**
		 * Return front page WP_Post
		 * @return array|null|\WP_Post
		 */
		public function get_front_page(){
			return get_post( get_option( 'page_on_front' ) );
		}


		/**
		 * Return blog WP_Post
		 * @return array|null|\WP_Post
		 */
		public function get_blog_page(){
			if(get_option( 'page_for_posts' ) == 0) return null;
			return get_post( get_option( 'page_for_posts' ) );
		}
	}
