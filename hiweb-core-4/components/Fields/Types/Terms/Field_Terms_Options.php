<?php
	
	namespace hiweb\components\Fields\Types\Terms;
	
	
	use hiweb\components\Fields\Field_Options;
	
	
	class Field_Terms_Options extends Field_Options{
		
		/**
		 * @param null|array $set
		 * @return $this|null|array
		 */
		public function taxonomy( $set = null ){
			if(is_string($set)) $set = [$set];
			return $this->_( __FUNCTION__, $set, ['category'] );
		}
		
		
		/**
		 * @param null|string $set
		 * @return $this|string
		 */
		public function placeholder( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param null|string $set
		 * @return $this|string
		 */
		public function no_results_text( $set = null ){
			return $this->_( __FUNCTION__, $set, 'Terms not found...' );
		}
		
		
		/**
		 * @param null|bool $set
		 * @return $this|null
		 */
		public function hide_empty( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param null|bool $set
		 * @return array|Field_Terms_Options|mixed|bool
		 */
		public function multiple($set = null){
			return $this->_(__FUNCTION__, $set, true);
		}
		
	}