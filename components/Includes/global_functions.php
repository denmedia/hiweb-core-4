<?php

	use hiweb\components\Includes\AdminPage;
	use hiweb\components\Includes\Css;
	use hiweb\components\Includes\FrontendPage;
	use hiweb\components\Includes\IncludesFactory;
	use hiweb\components\Includes\Js;


	if( !function_exists( 'include_js' ) ){

		/**
		 * @param       $fileNameOrPathOrURL
		 * @param array $deeps
		 * @param bool  $set_toFooter
		 * @return Js
		 */
		function include_js( $fileNameOrPathOrURL, $deeps = [], $set_toFooter = true ){
			$Js = IncludesFactory::Js( $fileNameOrPathOrURL );
			$Js->deeps( $deeps );
			$Js->to_footer( $set_toFooter );
			$Js->defer();
			return $Js;
		}
	}

	if( !function_exists( 'include_frontend_js' ) ){
		/**
		 * @param       $fileNameOrPathOrURL
		 * @param array $deeps
		 * @param bool  $set_toFooter
		 * @return Js
		 */
		function include_frontend_js( $fileNameOrPathOrURL, $deeps = [], $set_toFooter = true ){
			$Js = FrontendPage::Js( $fileNameOrPathOrURL );
			$Js->deeps( $deeps );
			$Js->to_footer( $set_toFooter );
			$Js->defer();
			return $Js;
		}
	}

	if( !function_exists( 'include_admin_js' ) ){

		function include_admin_js( $fileNameOrPathOrURL, $deeps = [], $set_toFooter = true ){
			$Js = AdminPage::Js( $fileNameOrPathOrURL );
			$Js->deeps( $deeps );
			$Js->to_footer( $set_toFooter );
			$Js->defer();
			return $Js;
		}
	}

	if( !function_exists( 'include_css' ) ){
		/**
		 * @param       $fileNameOrPathOrURL
		 * @param array $deeps
		 * @param bool  $set_toFooter
		 * @return Css
		 */
		function include_css( $fileNameOrPathOrURL, $deeps = [], $set_toFooter = false ){
			$Css = IncludesFactory::Css( $fileNameOrPathOrURL );
			$Css->deeps( $deeps );
			$Css->to_footer( $set_toFooter );
			return $Css;
		}
	}

	if( !function_exists( 'include_frontend_css' ) ){
		/**
		 * @param       $fileNameOrPathOrURL
		 * @param array $deeps
		 * @param bool  $set_toFooter
		 * @return Css
		 */
		function include_frontend_css( $fileNameOrPathOrURL, $deeps = [], $set_toFooter = false ){
			$Css = FrontendPage::Css( $fileNameOrPathOrURL );
			$Css->deeps( $deeps );
			$Css->to_footer( $set_toFooter );
			return $Css;
		}
	}

	if( !function_exists( 'include_admin_css' ) ){
		/**
		 * @param       $fileNameOrPathOrURL
		 * @param array $deeps
		 * @param bool  $set_toFooter
		 * @return Css
		 */
		function include_admin_css( $fileNameOrPathOrURL, $deeps = [], $set_toFooter = false ){
			$Css = AdminPage::Css( $fileNameOrPathOrURL );
			$Css->deeps( $deeps );
			$Css->to_footer( $set_toFooter );
			return $Css;
		}
	}