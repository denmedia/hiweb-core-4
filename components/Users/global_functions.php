<?php

	use hiweb\users\User;
	use hiweb\UsersFactory;


	if( !function_exists( 'get_user' ) ){
		/**
		 * @param null $userLoginOrId
		 * @return User
		 */
		function get_user( $userLoginOrId = null ){
			return UsersFactory::get( $userLoginOrId );
		}
	}