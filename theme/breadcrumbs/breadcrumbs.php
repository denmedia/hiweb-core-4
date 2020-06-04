<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/10/2018
	 * Time: 10:57
	 */

	namespace theme;


	use theme\breadcrumbs\crumb;
	use theme\includes\frontend;


	class breadcrumbs{

		private static $init = false;

		static $admin_options_slug = 'breadcrumbs';

		protected static $queried_object;
		//protected static $current_nav_menu_location;
		protected static $crumbs;
		protected static $crumbs_limit = 10;
		static $class = '';


		/**
		 *
		 */
		static function init(){
			if( self::$init ) return;
			self::$init = true;
			///
			require_once __DIR__ . '/options.php';
		}


		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}


		/**
		 * Print current breadcrumbs
		 */
		static function the(){
			self::init();
			frontend::fontawesome();
			self::$queried_object = get_queried_object();
			get_template_part( HIWEB_THEME_PARTS . '/breadcrumbs/wrap-prefix' );
			//items
			foreach( self::get_crumbs() as $index => $crumb ){
				if($crumb->wp_object instanceof \WP_Post_Type && !get_field('post-type-archive-show-' . $crumb->wp_object->name, breadcrumbs::$admin_options_slug)) continue;
				//console_info( [$crumb->get_url() ,$crumb->wp_object, ] )
				$crumb->the( $index + 1 );
				if( ( get_field( 'separator-enable', self::$admin_options_slug ) && ( $index + 1 ) < count( self::get_crumbs() ) ) || get_field( 'separator-last-enable', self::$admin_options_slug ) ){
					echo self::get_the_separator();
				}
			}
			//
			get_template_part( HIWEB_THEME_PARTS . '/breadcrumbs/wrap-sufix' );
		}


		/**
		 * Get shema.org data for print by json script string
		 * @return array
		 */
		static function get_shemaorg_data(){
			$R = [
				'@context' => 'https://schema.org/',
				'@type' => 'BreadcrumbList'
			];
			foreach( self::get_crumbs() as $index => $crumb ){
				$R['itemListElement'][] = [
					'@type' => 'ListItem',
					'position' => $index + 1,
					'item' => [
						'@id' => $crumb->get_link(),
						'name' => ( $crumb->get_parent_crumb() == false && get_field( 'home-enable', self::$admin_options_slug ) ) ? ( get_field( 'home-text', self::$admin_options_slug ) == '' ? get_bloginfo( 'name' ) : get_field( 'home-text', self::$admin_options_slug ) ) : $crumb->get_title()
					]
				];
			}
			return $R;
		}


		/**
		 * @return string
		 */
		static function get_the_separator(){
			ob_start();
			get_template_part( HIWEB_THEME_PARTS . '/breadcrumbs/item-separator' );
			$separator_icon = get_field( 'separator-icon', self::$admin_options_slug ) != '' ? '<i class="' . get_field( 'separator-icon', self::$admin_options_slug ) . '"></i>' : '';
			$separator_text = get_field( 'separator-text', self::$admin_options_slug );
			return strtr( ob_get_clean(), [ '{separator-icon}' => $separator_icon, '{separator-text}' => $separator_text ] );
		}


		/**
		 * @return string
		 */
		static function get_the(){
			ob_start();
			self::the();
			return ob_get_clean();
		}


		/**
		 * @version 1.2
		 * @return crumb[]
		 */
		static function get_crumbs(){
			if( !is_array( self::$crumbs ) ){
				self::$crumbs = [];
				$current_crumb = new crumb( get_queried_object() );
				if( apply_filters( '\theme\breadcrumbs::get_crumbs-current-enable', get_field( 'current-enable', self::$admin_options_slug ), $current_crumb ) ) self::$crumbs[] = $current_crumb;
				///
				$limit = self::$crumbs_limit;
				while( $limit > 0 && $current_crumb->get_parent_crumb() !== false ){
					$current_crumb = $current_crumb->get_parent_crumb();
					self::$crumbs[] = $current_crumb;
					$limit --;
				}
				///HOME CRUMB
				if( get_field( 'home-enable', self::$admin_options_slug ) && $current_crumb->get_parent_crumb() == false && !is_null(get_queried_object()) ){
					self::$crumbs[] = new crumb( '' );
				}
				///
				self::$crumbs = array_reverse( self::$crumbs );
			}
			if( count( self::$crumbs ) < 2 && !is_null(get_queried_object()) ) return [];
			return self::$crumbs;
		}

	}