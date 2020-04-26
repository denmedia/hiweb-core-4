<?php
	
	namespace hiweb\components\Fields\Types\File;
	
	
	use hiweb\components\Fields\Field_Options;
	
	
	class Field_File_Options extends Field_Options{
		
		/**
		 * Set / get label, if file not selected
		 * @param null $set
		 * @return array|Field_File_Options|mixed|null
		 */
		public function label_empty( $set = null ){
			return $this->_( 'label_empty', $set, __( 'File not select. Click or Upload file in to that place', 'hiweb-core-4' ) );
		}
		
		
		public function label_button_select($set = null){
			return $this->_('label_button_select', $set, __( 'Select file' ));
		}
		
	}