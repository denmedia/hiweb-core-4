<?php

	namespace hiweb\components\Includes;


    /**
     * Class IncludesFactory_FrontendPage
     * @package hiweb\components\Includes
     * @version 1.1
     */
	class IncludesFactory_FrontendPage extends IncludesFactory{

		/**
		 * @param null|string $fileNameOrPathOrURL
		 * @return Css
		 */
		public function css( $fileNameOrPathOrURL = null ){
			return parent::css( $fileNameOrPathOrURL )->on_frontend(true);
		}


        /**
         * @param null|string $fileNameOrPathOrURL
         * @param null        $deeps
         * @param bool        $defer
         * @return Js
         */
		public function js( $fileNameOrPathOrURL = null, $deeps = null, $defer = true ){
			return parent::js( $fileNameOrPathOrURL, $deeps );
		}

	}