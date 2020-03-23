<?php

	//BACKEND HOOKS
	//Post Type
	add_action( 'edit_form_top', '\hiweb\components\fields\Admin::edit_form_top' );
	add_action( 'edit_form_before_permalink', '\hiweb\components\fields\Admin::edit_form_before_permalink' );
	add_action( 'edit_form_after_title', '\hiweb\components\fields\Admin::edit_form_after_title' );
	add_action( 'edit_form_after_editor', '\hiweb\components\fields\Admin::edit_form_after_editor' );
	add_action( 'submitpost_box', '\hiweb\components\fields\Admin::submitpost_box' );
	add_action( 'submitpage_box', '\hiweb\components\fields\Admin::submitpost_box' );
	add_action( 'edit_form_advanced', '\hiweb\components\fields\Admin::edit_form_advanced' );
	add_action( 'edit_page_form', '\hiweb\components\fields\Admin::edit_form_advanced' );
	add_action( 'dbx_post_sidebar', '\hiweb\components\fields\Admin::dbx_post_sidebar' );
	//Post type Meta Box
	add_action( 'add_meta_boxes', '\hiweb\components\fields\Admin::add_meta_boxes', 8, 2 );
	///Posts List Columns
	add_action( 'manage_pages_custom_column', '\hiweb\components\fields\Admin::manage_posts_custom_column', 10, 2 );
	add_action( 'manage_posts_custom_column', '\hiweb\components\fields\Admin::manage_posts_custom_column', 10, 2 );
	add_filter( 'manage_pages_columns', '\hiweb\components\fields\Admin::manage_posts_columns', 10, 1 );
	add_filter( 'manage_posts_columns', '\hiweb\components\fields\Admin::manage_posts_columns', 10, 2 );
	//Sort Columns
	add_action( 'admin_init', function(){
		foreach( get_post_types() as $post_type ){
			add_filter( 'manage_edit-' . $post_type . '_sortable_columns', '\hiweb\components\fields\Admin::manage_posts_sortable_columns', 10, 1 );
		}
	} );
	///Post Save
	add_action( 'save_post', '\hiweb\components\fields\Admin::save_post', 10, 3 );
	//	////////
	///TAXONOMIES BACKEND
	add_action( 'init', function(){
		if( function_exists( 'get_taxonomies' ) && is_array( get_taxonomies() ) ) foreach( get_taxonomies() as $taxonomy_name ){
			//add
			add_action( $taxonomy_name . '_add_form_fields', '\hiweb\components\fields\Admin::taxonomy_add_form_fields' );
			//edit
			add_action( $taxonomy_name . '_edit_form', '\hiweb\components\fields\Admin::taxonomy_edit_form', 10, 2 );
		}
	}, 100 );
	///TAXONOMY SAVE
	add_action( 'created_term', '\hiweb\components\fields\Admin::taxonomy_edited_term', 10, 3 );
	add_action( 'edit_term', '\hiweb\components\fields\Admin::taxonomy_edited_term', 10, 3 );

	/// USERS SETTINGS
	/// USER ADD
	add_action( 'user_new_form', '\hiweb\components\fields\Admin::user_new_form' );
	/// USER EDIT
	add_action( 'admin_color_scheme_picker', '\hiweb\components\fields\Admin::admin_color_scheme_picker' );
	add_action( 'personal_options', '\hiweb\components\fields\Admin::personal_options' );
	add_action( 'profile_personal_options', '\hiweb\components\fields\Admin::profile_personal_options' );
	add_action( 'show_user_profile', '\hiweb\components\fields\Admin::edit_user_profile' );
	add_action( 'edit_user_profile', '\hiweb\components\fields\Admin::edit_user_profile' );
	/// USERS SAVE
	add_action( 'user_register', '\hiweb\components\fields\Admin::edit_user_profile_update' );
	add_action( 'personal_options_update', '\hiweb\components\fields\Admin::edit_user_profile_update' );
	add_action( 'edit_user_profile_update', '\hiweb\components\fields\Admin::edit_user_profile_update' );
	//	///OPTIONS FIELDS
	//	add_action( 'admin_init', [ $this, 'options_page_add_fields' ], 999999 );
	//	///ADMIN MENU FIELDS
	//	add_action( 'current_screen', [ $this, 'admin_menu_fields' ], 999999 );

	/// THEME SETTINGS
	add_action( 'customize_register', '\hiweb\components\fields\Admin::customize_register' );

	///COMMENTS
	add_action( 'add_meta_boxes_comment', '\hiweb\components\fields\Admin::add_meta_boxes_comment' );
	add_action( 'comment_edit_redirect', '\hiweb\components\fields\Admin::comment_edit_redirect', 10, 2 );