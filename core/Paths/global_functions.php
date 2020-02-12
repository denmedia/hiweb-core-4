<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 01:50
	 */

	use hiweb\core\Paths;


	if( !function_exists( 'get_path' ) ){
		/**
		 * @param string $path_or_url
		 * @return Paths\Path
		 */
		function get_path( $path_or_url = '' ){
			return Paths::get( $path_or_url );
		}
	}

	if( !function_exists( 'get_url' ) ){
		/**
		 * @param string $path_or_url
		 * @return Paths\Url
		 */
		function get_url( $path_or_url = '' ){
			return Paths::get( $path_or_url )->Url();
		}
	}

	if( !function_exists( 'get_file' ) ){
		/**
		 * Return File instance by path or urls
		 * @param string $path_or_url
		 * @return Paths\File
		 */
		function get_file( $path_or_url = '' ){
			return Paths::get( $path_or_url )->File();
		}
	}