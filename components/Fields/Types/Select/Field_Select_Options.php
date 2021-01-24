<?php
	
	namespace hiweb\components\Fields\Types\Select;
	
	
	use hiweb\components\Fields\Field_Options;
	
	
	class Field_Select_Options extends Field_Options{
		
		/**
		 * Set options list, like [val1 => label1, val2 => label2, ...]
		 * @param null|array $options
		 * @return array|Field_Select_Options|mixed|null
		 */
		public function options( $options = null ){
			return $this->_( 'options', $options, [] );
		}
		
		
		/**
		 * @param null|string $set
		 * @return array|Field_Select_Options|mixed|null
		 */
		public function placeholder($set = null){
			return $this->_('placeholder', $set, $this->multiple() ? __('Select options') : __('Select option'));
		}
		
		
		/**
		 * @param null|bool $set
		 * @return array|Field_Select_Options|mixed|null
		 */
		public function multiple($set = null){
			return $this->_('multiple', $set, false);
		}
		
	}