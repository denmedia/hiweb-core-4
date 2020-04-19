<?php
	
	namespace hiweb\components\Fields\Types\Content;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\components\Fields\Field_Options;
	
	
	class Field_Content_Options extends Field_Options{
		
		public function __construct( Field $Field ){
			parent::__construct( $Field );
			$this->editor_height(300);
		}
		
		
		/**
		 * @param null|int $set
		 * @return array|Field_Content_Options|mixed|null
		 */
		public function editor_height( $set = null ){
			return $this->_( 'editor_height', $set );
		}
		
	}