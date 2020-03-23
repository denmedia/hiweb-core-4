<?php

	namespace hiweb\components\fields\types\text;


	use hiweb\components\fields\options\Options;


	class Field_Text_Options extends Options{

		/**
		 * @param null $placeholder
		 * @return array|Field_Text_Options|mixed|null
		 */
		public function placeholder($placeholder = null){
			return $this->_('placeholder', $placeholder);
		}

	}