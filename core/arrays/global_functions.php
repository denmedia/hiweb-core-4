<?php

	use hiweb\core\arrays;
	use hiweb\core\arrays\Rows;


	if( !function_exists( 'get_array' ) ){
		/**
		 * Return instance of arrays\item
		 * @param array $array
		 * @return arrays\arrays
		 */
		function get_array( $array = [] ){
			return new arrays\Arrays( $array );
		}
	}


	if( !function_exists( 'get_rows' ) ){
		/**
		 * Return instance of arrays\item
		 * @param array $array
		 * @return Rows
		 */
		function get_rows( $array = [] ){
			return new Rows( $array );
		}
	}