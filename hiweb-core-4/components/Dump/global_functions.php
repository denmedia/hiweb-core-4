<?php
	
	namespace {
		
		
		use hiweb\components\Dump\Dump;
		
		
		if( !function_exists( 'dump_var' ) ){
			/**
			 * @param $variable
			 */
			function dump_var( $variable ){
				Dump::the( $variable );
			}
		}
	}