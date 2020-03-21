<?php

	namespace {


		if( !function_exists( 'include_js' ) ){
			/**
			 * @param       $jsPathOrURL
			 * @param array $deeps
			 * @param bool  $inFooter
			 * @return \hiweb\js\file
			 */
			function include_js( $jsPathOrURL, $deeps = [], $inFooter = true ){
				$file = hiweb\js::add( $jsPathOrURL );
				$file->add_deeps( $deeps );
				if( $inFooter ) $file->put_to_footer();
				return $file;
			}
		} else {
			hiweb\console::debug_warn( 'Function [include_js] is exists...' );
		}
	}

	namespace hiweb {


		/**
		 * @param string $jsPathOrURL
		 * @param array  $deeps
		 * @param bool   $inFooter
		 * @return js\file
		 */
		function js( $jsPathOrURL, $deeps = [], $inFooter = true ){
			$file = js::add( $jsPathOrURL );
			$file->add_deeps( $deeps );
			if( $inFooter ) $file->put_to_footer();
			return $file;
		}
	}