<?php

	namespace {


		use hiweb\admin;


		if( !function_exists( 'add_admin_menu_page' ) ){
			/**
			 * Add Adminb Menu Page by hiWeb
			 * @param $slug - option slug
			 * @param $title - admin menu title
			 * @param string $parent_slug - parent admin menu page slug, etc. "edit.php?post_type=page"
			 * @return admin\pages\page
			 */
			function add_admin_menu_page( $slug, $title, $parent_slug = null ){
				return admin::ADD_PAGE( $slug, $title, $parent_slug );
			}
		}

		if( !function_exists( 'add_admin_notice' ) ){
			/**
			 * Add admin notice by hiWeb
			 * @param string $content
			 * @param string $class
			 * @return admin\notices\notice
			 */
			function add_admin_notice( $content = '&nosp;', $class = 'notice notice-info is-dismissible' ){
				return admin::NOTICE( $content, $class );
			}
		}
	}