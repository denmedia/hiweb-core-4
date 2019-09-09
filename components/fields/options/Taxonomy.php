<?php

	namespace hiweb\components\fields\options;


	class Taxonomy extends \hiweb\core\arrays\Options{


		/**
		 * @param null $label
		 * @return array|PostType|mixed|null
		 */
		public function label( $label = null ){
			return $this->_( __FUNCTION__, $label );
		}


		/**
		 * @param null $description
		 * @return array|PostType|mixed|null
		 */
		public function description( $description = null ){
			return $this->_( __FUNCTION__, $description );
		}

	}