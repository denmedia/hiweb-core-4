<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 02:43
	 */

	namespace hiweb\components\css;


	use hiweb\css\file;


	class css{

		/** @var file[] */
		private static $styles = [];
		private static $already_printed = [];


		/**
		 * @param $pathOrUrl
		 * @return file
		 */
		static function add( $pathOrUrl ){
			require_once __DIR__ . '/-hooks.php';
			if( !array_key_exists( $pathOrUrl, self::$styles ) ){
				self::$styles[ $pathOrUrl ] = new file( $pathOrUrl );
			}
			return self::$styles[ $pathOrUrl ];
		}


		/**
		 * @return file[]
		 */
		static function get_styles(){
			return self::$styles;
		}


		static function _add_action_wp_register_script(){
			foreach( self::$styles as $handle => $css ){
				$enable = apply_filters( '\hiweb\css::_add_action_wp_register_script-enable', true, $css );
				if( $enable && ( !$css->is_local() || $css->is_exists() ) ){
					///REGISTER
					wp_register_style( $handle, $css->get_url(), $css->get_deeps(), filemtime( $css->get_path() ), $css->media()->get() );
					if( !$css->is_in_footer() || ( ( did_action( 'wp_footer' ) || did_action( 'admin_footer' ) ) && $css->is_in_footer() ) ){
						wp_enqueue_style( $handle );
					}
				} else {
					console::debug_error( '\hiweb\css::_add_action_wp_register_script: Не найден файл стиля [' . $css->get_path_relative() . ']' );
				}
			}
		}


		static function _the(){
			foreach( self::$styles as $handle => $css ){
				if(!Arrays::make(self::$already_printed)->in($handle)) $css->the();
				self::$already_printed[] = $handle;
			}
		}


		/**
		 * @param $html
		 * @param $handle
		 * @param $href
		 * @param $media
		 * @return null|string
		 */
		static public function _add_filter_style_loader_tag( $html, $handle, $href, $media ){
			if( array_key_exists( $handle, self::$styles ) ){
				$css = self::$styles[ $handle ];
				if( $css instanceof file ){
					if(!Arrays::make(self::$already_printed)->in($handle)) {
						self::$already_printed[] = $handle;
						return $css->html();
					} else {
						return '';
					}
				}
			}
			return $html;
		}

	}