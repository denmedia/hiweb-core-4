<?php
	
	namespace hiweb\components\Fields\Types\Image;
	
	
	use hiweb\components\Fields\Field_Options;
	
	
	class Field_Image_Options extends Field_Options{
		
		/**
		 * Set / get label, if file not selected
		 * @param null $set
		 * @return array|Field_Image_Options|mixed|null
		 */
		public function label_empty( $set = null ){
			return $this->_( 'label_empty', $set, __( 'File not select. Click or Upload file in to that place', 'hiweb-core-4' ) );
		}
		
		
		public function label_button_select( $set = null ){
			return $this->_( 'label_button_select', $set, __( 'Select file' ) );
		}
		
		
		/**
		 * Set 100% or pixel width, default 150px
		 * @param null $width
		 * @return array|Field_Image_Options|mixed|null
		 */
		public function admin_width( $width = null ){
			return $this->_( 'admin_width', $width, '150px' );
		}
		
		
		/**
		 * Set pixel height, default 120px
		 * @param null $height
		 * @return array|Field_Image_Options|mixed|null
		 */
		public function admin_height( $height = null ){
			return $this->_( 'admin_height', $height, '120px' );
		}
		
		
		/**
		 * @param null $width
		 * @return array|Field_Image_Options|mixed|null
		 * @deprecated
		 */
		public function preview_width( $width = null ){
			return $this->admin_width( $width );
		}
		
		
		/**
		 * @param null $height
		 * @return array|Field_Image_Options|mixed|null
		 * @deprecated
		 */
		public function preview_height( $height = null ){
			return $this->admin_height( $height );
		}
	}