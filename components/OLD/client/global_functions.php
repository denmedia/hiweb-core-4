<?php

	use hiweb\core\Client;


	if( !function_exists( 'get_client' ) ){
		/**
		 * @return Client
		 */
		function get_client(){
			return Client::get_instance();
		}
	}