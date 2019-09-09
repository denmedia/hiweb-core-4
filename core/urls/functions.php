<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 03/12/2018
	 * Time: 22:18
	 */

	if( !function_exists( 'get_url' ) ){
		/**
		 * @param null $url
		 * @return \hiweb\urls\url
		 */
		function get_url( $url = null ){
			return hiweb\Urls::get( $url );
		}
	}