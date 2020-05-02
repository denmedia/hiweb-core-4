<?php

	namespace hiweb\components;


	use hiweb\core\Paths\PathsFactory;


	class Context{

		/**
		 * @return bool
		 * @version 1.6
		 */
		static function is_frontend_page(){
			return ( preg_match( '/^\/index(-hiweb-cache)?\.php(\/.*)?$/i', $_SERVER['PHP_SELF'] ) > 0
			         && !self::is_rest_api() && !self::is_ajax()
			         && preg_match( '/(?>[\w\-_\.]+\.(xml|txt))/i', $_SERVER['REQUEST_URI'] ) == 0
			         && !self::is_feed() );
		}


		/**
		 * @return bool
		 */
		static function is_feed(){
			return \is_feed();
		}


		/**
		 * @param null|string|int|\WP_Post $postOrId
		 * @return bool
		 */
		static function is_front_page( $postOrId = null ){
			if( !is_null( $postOrId ) ){
				if( is_numeric( $postOrId ) && false ){
					return intval( $postOrId ) == get_option( 'page_on_front' );
				} elseif( is_string( $postOrId ) ) {
					$args = [
						'post_name' => $postOrId,
						'post_status' => 'publish',
						'post_per-Page' => 1
					];
					$my_posts = get_posts( $args );
					if( is_array( $my_posts ) && count( $my_posts ) > 0 ){
						return reset( $my_posts )->ID == get_option( 'page_on_front' );
					}
					return false;
				}
			}
			return \is_front_page();
		}


		/**
		 * @return bool
		 */
		static function is_admin_page(){
			if( self::is_ajax() || self::is_rest_api() ) return false;
			return \is_admin();
		}


		/**
		 * @return bool
		 */
		static function is_login_page(){
			return array_key_exists( $GLOBALS['pagenow'], array_flip( [
				'wp-login.php',
				'wp-register.php'
			] ) );
		}


		/**
		 * @return bool
		 */
		static function is_ajax(){
			return ( ( defined( 'DOING_AJAX' ) && DOING_AJAX == 1 ) || ( defined( 'WC_DOING_AJAX' ) && WC_DOING_AJAX == 1 ) );
		}


		/**
		 * @return bool
		 * @version 1.1
		 */
		static function is_rest_api(){
			return PathsFactory::get()->Url()->dirs()->get_value_by_index( 0 ) == 'wp-json' || ( isset( $_GET['rest_route'] ) );
		}


		/**
		 * Return TRUE, if context is CRON (request url domain.com/wp-cron.php)
		 * @return bool
		 */
		static function is_cron(){
			return defined( 'DOING_CRON' ) && DOING_CRON;
		}


		static function get_current_page(){
			return null;
			//return $this->get_current_page();
		}

	}