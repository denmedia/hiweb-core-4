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
		
		
		/**
		 * Set admin input filet size in pt, etc. '1.5' = 150%
		 * @param null $em
		 * @return array|Field_Text_Options|mixed|null
		 */
		public function font_size( $em = null ){
			return $this->_( 'font_size', $em, 1 );
		}
		
	}