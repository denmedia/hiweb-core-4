<?php

	if( !function_exists( 'hiweb' ) ){
		/**
		 * @return Hiweb
		 */
		function hiweb(){
			static $hiweb;
			if( !$hiweb instanceof Hiweb ) $hiweb = new Hiweb();
			return $hiweb;
		}
	}


	/**
	 * Core Object
	 */
	class Hiweb{

		/**
		 * @param $array_or_firstItem
		 * @return \hiweb\core\ArrayObject
		 */
		public function ArrayObject( $array_or_firstItem ){
			return new \hiweb\core\ArrayObject( $array_or_firstItem );
		}

	}
