<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory_Admin;
	use hiweb\core\Options\Options;
	
	
	class Field_Options_Location extends Options{
		
		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
		}
		
		
		/**
		 * @return Field_Options
		 */
		protected function getParent_OptionsObject(){
			return parent::getParent_OptionsObject();
		}
		
		
		/**
		 * @param null|string|string[] $post_type
		 * @return Field_Options_Location_PostType
		 */
		public function PostType( $post_type = null ){
			if( !$this->_( 'post_type' ) instanceof Field_Options_Location_PostType ){
				$this->_( 'post_type', new Field_Options_Location_PostType( $this ) );
				if( !is_null( $post_type ) ) $this->PostType()->post_type( $post_type );
			}
			return $this->_( 'post_type' );
		}
		
		
		/**
		 * @param null|string|string[] $taxonomy
		 * @return Field_Options_Location_Taxonomy
		 */
		public function Taxonomy( $taxonomy = null ){
			if( !$this->_( 'taxonomy' ) instanceof Field_Options_Location_Taxonomy ){
				$this->_( 'taxonomy', new Field_Options_Location_Taxonomy( $this ) );
				if( is_string( $taxonomy ) ) $taxonomy = [ $taxonomy ];
				if( is_array( $taxonomy ) ) $this->Taxonomy()->taxonomy( $taxonomy );
			}
			return $this->_( 'taxonomy' );
		}
		
		
		/**
		 * @return Field_Options_Location_User
		 */
		public function User(){
			if( !$this->_( 'user' ) instanceof Field_Options_Location_User ){
				$this->_( 'user', new Field_Options_Location_User( $this ) );
			}
			return $this->_( 'user' );
		}
		
		
		/**
		 * @param $page_slug
		 * @return string
		 */
		public function Options( $page_slug = null ){
			if( !is_null( $page_slug ) ){
				$this->_( 'options', $page_slug );
			}
			if( $this->getParent_OptionsObject()->Field()->get_allow_save_field() ){
				\register_setting( $page_slug, FieldsFactory_Admin::get_field_input_option_name( $this->getParent_OptionsObject()->Field() ) );
			}
			return $this->_( 'options', $page_slug );
		}
		
		
		/**
		 * @return Field_Options_Location_Form
		 */
		public function Form(){
			if( !$this->_( 'form' ) instanceof Field_Options_Location_Form ){
				$this->_( 'form', new Field_Options_Location_Form( $this ) );
			}
			return $this->_( 'form' );
		}
		
		
		///ALIAS
		
		
		/**
		 * @param null $page_slug
		 * @return string
		 * @alias $this->Options
		 */
		public function Admin_Menus( $page_slug = null ){
			return $this->Options( $page_slug );
		}
		
		
		/**
		 * @param null $post_type
		 * @return Field_Options_Location_PostType
		 */
		protected function Post_Types( $post_type = null ){
			return $this->PostType( $post_type );
		}
		
		
		/**
		 * @param $taxonomy
		 * @return Field_Options_Location_Taxonomy
		 */
		protected function Taxonomies($taxonomy) {
			return $this->Taxonomy($taxonomy);
		}
		
		
	}