<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/10/2018
	 * Time: 11:06
	 */

	namespace theme\breadcrumbs;


	use components\Taxonomy_Main_Select\Taxonomy_Main_Select;
	use theme\breadcrumbs;
	use theme\structures\structure;


	class crumb extends structure{

		protected $queried_object;
		protected $parent_object = false;
		protected $title;
		protected $link;
		protected $active = false;
		public $position = 0;


		public function __construct( $object ){
			parent::__construct( $object );
			$this->active = get_queried_object() == $this->wp_object;
		}


		/**
		 * @return array
		 */
		public function get_data(){
			return [
				'title' => $this->title,
				'link' => $this->link,
				'active' => $this->active,
				'parent_object' => $this->parent_object
			];
		}


		/**
		 * @param bool $force_raw
		 * @return mixed|string
		 */
		public function get_title( $force_raw = true ){
			//HOME PAGE
			if( $this->get_id() == '' ){
				$home_title = '';
				if( get_field( 'home-icon', breadcrumbs::$admin_options_slug ) != '' ){
					$home_title .= '<i class="' . get_field( 'home-icon', breadcrumbs::$admin_options_slug ) . '"></i> ';
				}
				if( !get_field( 'home-text-enable', breadcrumbs::$admin_options_slug ) ){
					//do nothing
				} elseif( get_field( 'home-text', breadcrumbs::$admin_options_slug ) != '' ) {
					$home_title .= get_field( 'home-text', breadcrumbs::$admin_options_slug );
				} else {
					$home_title .= get_bloginfo( 'name' );
				}
				return $home_title;
			}
			return parent::get_title( $force_raw );
		}


		/**
		 * @return mixed
		 */
		public function get_link(){
			return $this->get_url();
		}


		public function the( $position = 0 ){
			ob_start();
			get_template_part( HIWEB_THEME_PARTS . '/breadcrumbs/item-prefix' );
			///
			get_template_part( HIWEB_THEME_PARTS . '/breadcrumbs/item-title', ( ( $this->active && !get_field( 'current-url', breadcrumbs::$admin_options_slug ) ) || ( $this->get_link() === false ) ) ? '' : 'link' );
			///
			get_template_part( HIWEB_THEME_PARTS . '/breadcrumbs/item-sufix' );
			echo strtr( ob_get_clean(), [ '{link}' => $this->get_link(), '{title}' => $this->get_title(), '{active-class}' => $this->active ? 'active' : '', '{position}' => $position ] );
		}


		/**
		 * @return bool
		 */
		public function is_home(){
			return $this->id == '';
		}


		/**
		 * @return mixed
		 * @version 1.1
		 */
		public function get_parent_object(){
			$candidates = $this->get_parent_wp_objects();
			if( !is_array( $candidates ) || count( $candidates ) == 0 ) return false;
			///hiWeb Core main term
			if(class_exists('\components\Taxonomy_Main_Select\Taxonomy_Main_Select') && Taxonomy_Main_Select::is_init() ){
				foreach( $candidates as $candidate ){
					if( $candidate instanceof \WP_Term && $candidate->term_id == get_post_meta( $this->wp_object->ID, Taxonomy_Main_Select::$meta_key . '-' . $candidate->taxonomy, true ) ) return $candidate;
				}
			}
			///Yoast SEO main term
			if( $this->wp_object instanceof \WP_Post && get_post_meta( $this->wp_object->ID, '_yoast_wpseo_primary_product_cat', true ) != '' ){
				foreach( $candidates as $candidate ){
					if( $candidate instanceof \WP_Term && $candidate->term_id == get_post_meta( $this->wp_object->ID, '_yoast_wpseo_primary_product_cat', true ) ) return $candidate;
				}
			}
			foreach( $candidates as $candidate ){
				if( $candidate instanceof \WP_Term ){
					if( get_field( 'taxonomy-' . $candidate->taxonomy . '-enable', breadcrumbs::$admin_options_slug ) ) return $candidate;
				} else {
					return $candidate;
				}
			}
			return ( new crumb( reset( $candidates ) ) )->get_parent_object();
			//return reset( $this->get_parent_wp_objects() );
		}


		/**
		 * @return bool|crumb
		 */
		public function get_parent_crumb(){
			$parent_object = $this->get_parent_object();
			if( $parent_object === false ) $R = false; else $R = new crumb( $this->get_parent_object() );
			return $R;
		}

	}