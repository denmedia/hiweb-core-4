<?php
	
	namespace hiweb\components\Fields\Types\Textarea;
	
	
	use hiweb\components\Fields\Field_Options;
	
	
	class Field_Textarea_Options extends Field_Options{
		
		/**
		 * @param null $set
		 * @return array|Field_Textarea_Options|mixed|null
		 */
		public function rows($set = null){
			return $this->_('rows', $set, 5);
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Textarea_Options|mixed|null
		 */
		public function placeholder($set = null){
			return $this->_('placeholder', $set);
		}
	
	}