<?php

	namespace hiweb\components\Includes;


	class LoginPage extends IncludesFactory{

		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Css
		 */
		public static function Css( $fileNameOrPathOrURL = null ){
			return parent::Css( $fileNameOrPathOrURL )->on_login( true );
		}


		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Js
		 */
		public static function Js( $fileNameOrPathOrURL = null ){
			return parent::Js( $fileNameOrPathOrURL )->on_login( true );
		}

	}