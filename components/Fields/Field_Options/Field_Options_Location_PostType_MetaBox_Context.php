<?php

	namespace hiweb\components\Fields\Field_Options;


	use hiweb\core\Options\Options;
	use hiweb\core\Options\Options_Once;


	class Field_Options_Location_PostType_MetaBox_Context extends Options_Once{

		/**
		 * @return Field_Options_Location_PostType_MetaBox
		 */
		public function normal(): Field_Options_Location_PostType_MetaBox {
			return $this->_( 'normal' );
		}


		/**
		 * @return Field_Options_Location_PostType_MetaBox
		 */
		public function advanced(): Field_Options_Location_PostType_MetaBox {
			return $this->_( 'advanced' );
		}


		/**
		 * @return Field_Options_Location_PostType_MetaBox
		 */
		public function side(): Field_Options_Location_PostType_MetaBox {
			return $this->_( 'side' );
		}

	}