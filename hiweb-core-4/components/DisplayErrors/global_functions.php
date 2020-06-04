<?php

	use hiweb\components\DisplayErrors\DisplayErrors;


	if( !function_exists( 'init_displayErrors' ) ){
		/**
		 * Display fatal errors
		 * @param bool $showBacktrace
		 * @return bool
		 */
		function init_displayErrors( $showBacktrace = false ){
			return DisplayErrors::init( $showBacktrace );
		}
	}

	if( !function_exists( 'errors_display' ) ){
		/**
		 * Display fatal errors
		 * @param bool $showBacktrace
		 * @return bool
		 */
		function errors_display( $showBacktrace = false ){
			return DisplayErrors::init( $showBacktrace );
		}
	}

	if( !function_exists( 'display_errors' ) ){
		/**
		 * Display fatal errors
		 * @param bool $showBacktrace
		 * @return bool
		 */
		function display_errors( $showBacktrace = false ){
			return DisplayErrors::init( $showBacktrace );
		}
	}