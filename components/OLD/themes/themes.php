<?php

	namespace hiweb;


	class themes{

		/**
		 * @var array|themes\theme[]
		 */
		static private $themes = [];


		/**
		 * @param null $theme_name
		 * @return mixed
		 */
		static private function get_curretn_theme_name( $theme_name = null ){
			return trim( $theme_name ) == '' ? get_option( 'stylesheet' ) : $theme_name;
		}


		static function is_exists( $theme_name ){
			return wp_get_theme( $theme_name )->exists();
		}


		/**
		 * @return themes\theme
		 */
		function current(){
			return themes::get();
		}


		/**
		 * @param null $theme_name
		 * @return themes\theme
		 */
		static function get( $theme_name = null ){
			$theme_name = self::get_curretn_theme_name( $theme_name );
			if( !array_key_exists( $theme_name, self::$themes ) && self::is_exists( $theme_name ) ){
				self::$themes[ $theme_name ] = new themes\theme( $theme_name );
			}
			return self::$themes[ $theme_name ];
		}

	}