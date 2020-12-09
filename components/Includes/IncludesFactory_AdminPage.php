<?php

	namespace hiweb\components\Includes;


	class IncludesFactory_AdminPage extends IncludesFactory{

		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Css
         * @version 1.1
		 */
		public function css( $fileNameOrPathOrURL = null ){
			return parent::css( $fileNameOrPathOrURL )->on_admin( true );
		}


        /**
         * @param null|string $fileNameOrPathOrURL
         * @param null        $deeps
         * @param bool        $defer
         * @return Js
         */
		public function js( $fileNameOrPathOrURL = null, $deeps = null, $defer = true ){
			return parent::js( $fileNameOrPathOrURL, $deeps )->on_frontend(true);
		}

	}