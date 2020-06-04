<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\core\Options\Options;
	
	
	class Field_Options_Location_AdminMenu extends Options{
		
		public function page_slug( $set = null ){
			return $this->_( 'page_slug', $set );
		}
		
		
		
		
	}