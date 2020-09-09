<?php

	namespace hiweb\components\Includes;


	class IncludesFactory_LoginPage extends IncludesFactory{

		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Css
		 */
		public static function css( $fileNameOrPathOrURL = null ){
			return parent::css( $fileNameOrPathOrURL )->on_login( true );
		}
		
		
		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @param null        $deeps
		 * @return Js
		 */
		public static function js( $fileNameOrPathOrURL = null, $deeps = null ){
			return parent::js( $fileNameOrPathOrURL, $deeps )->on_frontend(true);
		}

	}