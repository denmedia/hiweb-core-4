<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-02
	 * Time: 22:46
	 */

	if( !function_exists( 'console_debug_backtrace' ) ){
		function console_debug_backtrace( $depth = 0 ){
			Backtrace::point( (int)$depth + 1 )->the_console_nodes();
		}
	}