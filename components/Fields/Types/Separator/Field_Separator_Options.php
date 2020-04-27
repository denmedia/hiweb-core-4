<?php
	
	namespace hiweb\components\Fields\Types\Separator;
	
	
	use hiweb\components\Fields\Field_Options;
	
	
	class Field_Separator_Options extends Field_Options{
		
		/**
		 * @param null|string $set
		 * @return array|Field_Separator_Options|mixed|null
		 */
		public function separator_label( $set = null ){
			return $this->_( 'separator_label', $set );
		}
		
		
		/**
		 * @param null|string $set
		 * @return array|Field_Separator_Options|mixed|null
		 */
		public function separator_description( $set = null ){
			return $this->_( 'separator_description', $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Separator_Options|mixed|null
		 */
		public function tag_label( $set = null ){
			return $this->_( 'tag_label', $set, 'h2' );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Separator_Options|mixed|null
		 */
		public function tag_description( $set = null ){
			return $this->_( 'tag_description', $set, 'p' );
		}
		
	}