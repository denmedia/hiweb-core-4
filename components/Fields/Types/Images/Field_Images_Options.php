<?php
	
	namespace hiweb\components\Fields\Types\Images;
	
	
	use hiweb\components\Fields\Field_Options;
	
	
	class Field_Images_Options extends Field_Options{
		
		/**
		 * @param null|string $set
		 * @return array|Field_Images_Options|mixed|null
		 */
		public function label_top( $set = null ){
			return $this->_( 'label_top', $set, __( 'Select images', 'hiweb-core-4' ) );
		}
		
	}