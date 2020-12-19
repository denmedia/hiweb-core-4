<?php

	namespace hiweb\components\Includes;


    /**
     * Class IncludesFactory_LoginPage
     * @package hiweb\components\Includes
     * @version 1.1
     */
	class IncludesFactory_LoginPage extends IncludesFactory{

		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Css
		 */
		public function css( $fileNameOrPathOrURL = null ): Css{
			return parent::css( $fileNameOrPathOrURL )->on_login( true );
		}


        /**
         * @param null|string $fileNameOrPathOrURL
         * @param null        $deeps
         * @param bool        $defer
         * @return Js
         */
		public function js( $fileNameOrPathOrURL = null, $deeps = null, $defer = true ): Js{
			return parent::js( $fileNameOrPathOrURL, $deeps )->on_frontend(true);
		}

	}