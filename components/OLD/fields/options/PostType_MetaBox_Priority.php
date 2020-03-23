<?php

	namespace hiweb\components\fields\options;


	use hiweb\core\ArrayObject\Options_Once;


	class PostType_MetaBox_Priority extends Options_Once{

		/**
		 * @return PostType_MetaBox
		 */
		public function high(){
			return $this->set( '', __FUNCTION__ );
		}


		/**
		 * @return PostType_MetaBox
		 */
		public function low(){
			return $this->set( '', __FUNCTION__ );
		}


		/**
		 * @return PostType_MetaBox
		 */
		public function default(){
			return $this->set( '', __FUNCTION__ );
		}

	}