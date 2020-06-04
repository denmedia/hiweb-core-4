<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 16:22
	 */

	///Languages Tools
	use theme\languages;


	if( !function_exists( 'get_languages' ) ){
		/**
		 * @return languages\includes_old\language[]
		 */
		function get_languages(){
			return languages::get_languages();
		}
	}

	if( !function_exists( 'get_current_language' ) ){
		/**
		 * @return languages\includes_old\language
		 */
		function get_current_language(){
			return languages::get_current_language();
		}
	}

	if( !function_exists( 'get_language_date' ) ){
		/**
		 * @param $timestamp
		 * @return string
		 */
		function get_language_date( $timestamp ){
			return languages::get_date( $timestamp );
		}
	}