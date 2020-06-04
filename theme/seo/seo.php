<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-21
	 * Time: 11:18
	 */
	
	namespace theme;
	
	
	use hiweb\components\AdminMenu\AdminMenu_Page;
	
	
	class seo{
		
		private static $init = false;
		/**
		 * @deprecated
		 * @var bool
		 */
		static $option_force_redirect_slash_end = false;
		static $option_page_permalink_force_slash_end = false;
		static $option_term_permalink_force_slash_end = false;
		static $option_use_paginate_canonical = true;
		
		static $admin_menu_main = 'hiweb-seo-main';
		static $admin_menu_main_parent = 'options-general.php';
		/** @var AdminMenu_Page */
		static $admin_menu_main_page;
		
		
		static function init(){
			if( !self::$init ){
				self::$init = true;
				require_once __DIR__ . '/options.php';
				require_once __DIR__ . '/hooks.php';
				require_once __DIR__ . '/post-type-meta.php';
				require_once __DIR__ . '/authors.php';
				require_once __DIR__ . '/global_functions.php';
			}
		}
		
		
		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}
		
		
		/**
		 * @param string $post_type
		 * @return mixed|null
		 */
		static function get_post_type_title( $post_type = 'post' ){
			$post_type = get_post_type_object( $post_type );
			if( $post_type->has_archive ){
				return get_field( 'archive-title-' . $post_type->name, self::$admin_menu_main );
			}
			return null;
		}
		
		
		/**
		 * @return mixed
		 */
		static function is_author_enable(){
			return get_field( 'authors-enable', self::$admin_menu_main );
		}
		
		
		/**
		 * @return mixed
		 */
		static function is_paged_append_enable(){
			return get_field( 'paged-append-enable', self::$admin_menu_main );
		}
		
		
	}