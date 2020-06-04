<?php

	use hiweb\components\Console\Console;
	use hiweb\components\Console\ConsoleFactory;


	if( !function_exists( 'console_info' ) ){
		/**
		 * Print the console.info()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\components\console\Console
		 */
		function console_info( $info, $groupTitle = '', $additionData = [] ){
			return ConsoleFactory::add( $info, 'info', $groupTitle, $additionData );
		}
	}

	if( !function_exists( 'console_warn' ) ){
		/**
		 * Print the console.warn()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\components\console\Console
		 */
		function console_warn( $info, $groupTitle = '', $additionData = [] ){
			return ConsoleFactory::add( $info, 'warn', $groupTitle, $additionData );
		}
	}

	if( !function_exists( 'console_error' ) ){
		/**
		 * Print the console.error()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\components\console\Console
		 */
		function console_error( $info, $groupTitle = '', $additionData = [] ){
			return ConsoleFactory::add( $info, 'error', $groupTitle, $additionData );
		}
	}

	if( !function_exists( 'console_log' ) ){
		/**
		 * Print the console.log()
		 * @param        $info
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return hiweb\components\console\Console
		 */
		function console_log( $info, $groupTitle = '', $additionData = [] ){
			return ConsoleFactory::add( $info, 'log', $groupTitle, $additionData );
		}
	}
