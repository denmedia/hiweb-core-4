<?php

	use hiweb\errors\display;

	if(!function_exists('errors_display')){
		function errors_display( $showBacktrace = false ){
			return display::enable( $showBacktrace );
		}
	}

	if(!function_exists('display_errors')){
		function display_errors( $showBacktrace = false ){
			return display::enable( $showBacktrace );
		}
	}


