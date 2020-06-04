<?php
	
	namespace hiweb\components\Fields\Types\Tab;
	
	
	class Field_Tab_Options extends \hiweb\components\Fields\Field_Options{
		
		/**
		 * @param null $font_awesome_icon
		 * @return array|Field_Tab_Options|mixed|null
		 */
		public function icon($font_awesome_icon = null){
			return $this->_('icon', $font_awesome_icon);
		}
	
	}