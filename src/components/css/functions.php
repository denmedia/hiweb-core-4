<?php

	namespace {


		use hiweb\css;


		if( !function_exists( 'include_css' ) ){
			/**
			 * @param        $filePathOrUrl
			 * @param bool   $in_footer
			 * @param array  $deeps
			 * @param string $media
			 * @return css\file
			 */
			function include_css( $filePathOrUrl, $in_footer = false, $deeps = [], $media = 'all' ){
				$filePathOrUrl = apply_filters( 'hiweb-core-include_css', $filePathOrUrl, $deeps, $media, $in_footer );
				$css = css::add( $filePathOrUrl );
				if( $in_footer ) $css->put_to_footer();
				return $css;
				//return hiweb\css\enqueue::add( $filePathOrUrl, $deeps, $media, $in_footer );
			}
		} else {
			hiweb\console::debug_warn( 'Function [include_css] is exists...' );
		}
	}

	namespace hiweb\components\css {


		/**
		 * Поставить в очередь файл CSS
		 * @deprecated
		 * @version  2.0
		 * @param string $filePathOrUrl
		 * @param bool   $in_footer
		 * @return css\file
		 */
		function css( $filePathOrUrl, $in_footer = false ){
			$css = css::add( $filePathOrUrl );
			if( $in_footer ) $css->put_to_footer();
			return $css;
		}
	}