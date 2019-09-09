<?php

	namespace hiweb;


	class users{

		//use hw_hidden_methods_props;

		/** @var users\user[] */
		static private $users = [];


		/**
		 * Возвращает корневой класс для работы с данными пользователя
		 * @param $idOrLoginOrEmail - если не указывать, то будет взят текущий авторизированный пользователь
		 * @return users\user
		 */
		static function get( $idOrLoginOrEmail = null ){
			///
			if( is_null( $idOrLoginOrEmail ) ){
				require_once ABSPATH . '/wp-includes/pluggable.php';
				$current_user = wp_get_current_user();
				if( $current_user instanceof \WP_User ) $idOrLoginOrEmail = $current_user->ID;
			}
			///
			if( !isset( self::$users[ $idOrLoginOrEmail ] ) ){
				$user = new users\user( $idOrLoginOrEmail );
				self::$users[ $idOrLoginOrEmail ] = $user;
				if( $user->is_exist() ){
					self::$users[ $user->id() ] = $user;
					self::$users[ $user->login() ] = $user;
					self::$users[ $user->email() ] = $user;
				}
			}
			return self::$users[ $idOrLoginOrEmail ];
		}

	}
