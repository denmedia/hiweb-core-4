<?php
	
	namespace hiweb\components\Fields\Types\Date;
	
	
	use hiweb\components\Fields\Field_Options;
	
	
	class Field_Date_Options extends Field_Options{
		
		public function placeholder($set = null) {
			return $this->_('placeholder', $set, 'YYYY-MM-DD');
		}
		
	}