<?php

	namespace hiweb\components\Includes;


	class IncludesFactory_AdminPage extends IncludesFactory{

		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Css
		 */
		public function css( $fileNameOrPathOrURL = null ){
			return parent::css( $fileNameOrPathOrURL )->on_admin( true );
		}
		
		
		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @param null        $deeps
		 * @return Js
		 */
		public function js( $fileNameOrPathOrURL = null, $deeps = null ){
			return parent::js( $fileNameOrPathOrURL, $deeps )->on_frontend(true);
		}

	}