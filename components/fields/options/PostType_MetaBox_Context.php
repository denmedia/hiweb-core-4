<?php

	namespace hiweb\components\fields\options;


	class PostType_MetaBox_Context extends \hiweb\core\ArrayObject\Options_Once{

		/**
		 * @return PostType_MetaBox
		 */
		public function normal(){
			return $this->_( 0, __FUNCTION__ );
		}


		/**
		 * @return PostType_MetaBox
		 */
		public function advanced(){
			return $this->_( 0, __FUNCTION__ );
		}


		/**
		 * @return PostType_MetaBox
		 */
		public function side(){
			return $this->_( 0, __FUNCTION__ );
		}
	}