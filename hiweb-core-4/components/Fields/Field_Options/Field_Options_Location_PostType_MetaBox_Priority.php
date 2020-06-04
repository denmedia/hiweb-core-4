<?php

	namespace hiweb\components\Fields\Field_Options;


	use hiweb\core\Options\Options;
	use hiweb\core\Options\Options_Once;


	class Field_Options_Location_PostType_MetaBox_Priority extends Options_Once{

		/**
		 * @return array|Options|mixed|null
		 */
		public function high(){
			return $this->_( 'high' );
		}


		/**
		 * @return array|Options|mixed|null
		 */
		public function low(){
			return $this->_( 'low' );
		}


		/**
		 * @return array|Options|mixed|null
		 */
		public function default_(){
			return $this->_( 'default' );
		}

	}