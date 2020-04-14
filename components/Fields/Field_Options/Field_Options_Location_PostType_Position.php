<?php

	namespace hiweb\components\Fields\Field_Options;


	use hiweb\core\Options\Options_Once;


	class Field_Options_Location_PostType_Position extends Options_Once{


		/**
		 * @return Field_Options_Location_PostType
		 */
		public function clear(){
			return $this->_( '' );
		}


		/**
		 * @return Field_Options_Location_PostType
		 */
		public function edit_form_top(){
			return $this->_( __FUNCTION__ );
		}


		/**
		 * @return Field_Options_Location_PostType
		 */
		public function edit_form_before_permalink(){
			return $this->_( __FUNCTION__ );
		}


		/**
		 * @return Field_Options_Location_PostType
		 */
		public function edit_form_after_title(){
			return $this->_( __FUNCTION__ );
		}


		/**
		 * @return Field_Options_Location_PostType
		 */
		public function edit_form_after_editor(){
			return $this->_( __FUNCTION__ );
		}


		/**
		 * @return Field_Options_Location_PostType
		 */
		public function submitpost_box(){
			return $this->_( __FUNCTION__ );
		}


		/**
		 * @return Field_Options_Location_PostType
		 */
		public function edit_form_advanced(){
			return $this->_( __FUNCTION__ );
		}


		/**
		 * @return Field_Options_Location_PostType
		 */
		public function dbx_post_sidebar(){
			return $this->_( __FUNCTION__ );
		}

	}