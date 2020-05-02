<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\components\Fields\FieldsFactory_Admin;
	use hiweb\core\Options\Options;
	
	
	class Field_Options_Location_PostType_ColumnsManager extends Options{
		
		/**
		 * @return Field
		 */
		protected function Field(){
			return $this->getRoot_OptionsObject()->Field();
		}
		
		
		public function id( $set = null ){
			return $this->_( 'id', $set, FieldsFactory_Admin::get_columns_field_id( $this->Field()->ID() ) );
		}
		
		
		public function position( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		public function name( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		public function callback( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		public function sortable( $set = null ){
			return $this->_( __FUNCTION__, $set, false );
		}
		
	}