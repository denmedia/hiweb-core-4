<?php

	use hiweb\components\console\Console;


	if( !function_exists( 'console_info' ) ){
		/**
		 * Print the console.info()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\components\console\Message
		 */
		function console_info( $info, $groupTitle = '', $additionData = [] ){
			return Console::info( $info, $groupTitle, $additionData );
		}
	}

	if( !function_exists( 'console_warn' ) ){
		/**
		 * Print the console.warn()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\components\console\Message
		 */
		function console_warn( $info, $groupTitle = '', $additionData = [] ){
			return Console::warn( $info, $groupTitle, $additionData );
		}
	}

	if( !function_exists( 'console_error' ) ){
		/**
		 * Print the console.error()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\components\console\Message
		 */
		function console_error( $info, $groupTitle = '', $additionData = [] ){
			return Console::error( $info, $groupTitle, $additionData );
		}
	}

	if( !function_exists( 'console_log' ) ){
		/**
		 * Print the console.log()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\components\console\Message
		 */
		function console_log( $info, $groupTitle = '', $additionData = [] ){
			return Console::log( $info, $groupTitle, $additionData );
		}
	}
