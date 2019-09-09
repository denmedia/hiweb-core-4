<?php

	namespace hiweb\components\fields\options;


	use hiweb\core\arrays\Options_Once;


	class PostType_Position extends Options_Once{


		/**
		 * @return PostType
		 */
		public function edit_form_top(){
			return $this->_( 'position', __FUNCTION__ );
		}


		/**
		 * @return PostType
		 */
		public function edit_form_before_permalink(){
			return $this->_( 'position', __FUNCTION__ );
		}


		/**
		 * @return PostType
		 */
		public function edit_form_after_title(){
			return $this->_( 'position', __FUNCTION__ );
		}


		/**
		 * @return PostType
		 */
		public function edit_form_after_editor(){
			return $this->_( 'position', __FUNCTION__ );
		}


		/**
		 * @return PostType
		 */
		public function submitpost_box(){
			return $this->_( 'position', __FUNCTION__ );
		}


		/**
		 * @return PostType
		 */
		public function edit_form_advanced(){
			return $this->_( 'position', __FUNCTION__ );
		}


		/**
		 * @return PostType
		 */
		public function dbx_post_sidebar(){
			return $this->_( 'position', __FUNCTION__ );
		}

	}