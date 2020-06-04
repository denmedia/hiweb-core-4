<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 16:21
	 */

	use theme\forms;


	if( !function_exists( 'the_form' ) ){

		/**
		 * @param null $form_post_id
		 * @return forms\form
		 */
		function get_form( $form_post_id = null ){
			return forms::get( $form_post_id );
		}
	}

	if( !function_exists( 'get_the_form' ) ){

		/**
		 * @return forms\form
		 */
		function get_the_form(){
			return forms::get_the_form();
		}
	}

	if( !function_exists( 'get_the_form_id' ) ){
		/**
		 * @return int|null
		 */
		function get_the_form_id(){
			return forms::get_the_ID();
		}
	}

	if( !function_exists( 'have_form_inputs' ) ){
		/**
		 * @return bool
		 */
		function have_form_inputs(){
			return forms::get_the_form()->have_inputs();
		}
	}

	if( !function_exists( 'the_form_input' ) ){
		/**
		 * @return forms\inputs\input
		 */
		function the_form_input(){
			return forms::get_the_form()->the_input();
		}
	}

	if( !function_exists( 'the_form_input_html' ) ){
		/**
		 * Print the form input HTML
		 */
		function the_form_input_html(){
			forms::get_the_form()->get_the_input()->the();
		}
	}