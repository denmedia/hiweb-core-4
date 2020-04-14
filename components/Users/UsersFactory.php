<?php

	namespace hiweb;


	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;


	class UsersFactory{


		/**
		 * Возвращает корневой класс для работы с данными пользователя
		 * @param $idOrLoginOrEmail - если не указывать, то будет взят текущий авторизированный пользователь
		 * @return Users\User
		 */
		static function get( $idOrLoginOrEmail = null ){
			///
			if( is_null( $idOrLoginOrEmail ) ){
				require_once ABSPATH . '/wp-includes/pluggable.php';
				$current_user = wp_get_current_user();
				if( $current_user instanceof \WP_User ) $idOrLoginOrEmail = $current_user->ID;
			}
			return CacheFactory::get( $idOrLoginOrEmail, __CLASS__ . '::$users', function(){
				$user = new users\User( func_get_arg( 0 ) );
				if( $user->is_exist() ){
					CacheFactory::set( $user, $user->id(), __CLASS__ . '::$users' );
					CacheFactory::set( $user, $user->login(), __CLASS__ . '::$users' );
					CacheFactory::set( $user, $user->email(), __CLASS__ . '::$users' );
				}
			}, [ $idOrLoginOrEmail ] )->get_value();
		}

	}