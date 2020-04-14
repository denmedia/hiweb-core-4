<?php

	namespace hiweb\components\Fields\Types\Checkbox;


	use hiweb\components\Fields\Field_Options;


	class Field_Checkbox_Options extends Field_Options{

		/**
		 * @param null $set
		 * @return array|Field_Checkbox_Options|mixed|null
		 */
		public function label_checkbox( $set = null ){
			return $this->_( 'label_checkbox', $set );
		}

	}