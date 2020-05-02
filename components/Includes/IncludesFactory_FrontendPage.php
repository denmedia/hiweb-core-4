<?php

	namespace hiweb\components\Includes;



	class IncludesFactory_FrontendPage extends IncludesFactory{

		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Css
		 */
		public static function css( $fileNameOrPathOrURL = null ){
			return parent::css( $fileNameOrPathOrURL )->on_frontend(true);
		}


		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Js
		 */
		public static function js( $fileNameOrPathOrURL = null ){
			return parent::js( $fileNameOrPathOrURL )->on_frontend(true);
		}

	}