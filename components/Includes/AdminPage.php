<?php

	namespace hiweb\components\Includes;


	class AdminPage extends IncludesFactory{

		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Css
		 */
		public static function Css( $fileNameOrPathOrURL = null ){
			return parent::Css( $fileNameOrPathOrURL )->on_admin( true );
		}


		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Js
		 */
		public static function Js( $fileNameOrPathOrURL = null ){
			return parent::Js( $fileNameOrPathOrURL )->on_admin( true );
		}

	}