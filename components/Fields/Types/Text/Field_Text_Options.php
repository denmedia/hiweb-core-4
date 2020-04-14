<?php

	namespace hiweb\components\Fields\Types\Text;


	use hiweb\components\Fields\Field_Options;


	class Field_Text_Options extends Field_Options{


		/**
		 * @param null $set
		 * @return array|Field_Text_Options|mixed|null
		 */
		public function placeholder( $set = null ){
			return $this->_( 'placeholder', $set );
		}

	}