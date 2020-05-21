<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\FieldsFactory_Admin;
	use hiweb\core\Options\Options;
	
	
	class Field_Options_Location extends Options{
		
		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
		}
		
		
		public function __clone(){
			$this->Options = clone $this->Options;
			if( $this->_( 'post_type' ) instanceof Field_Options_Location_PostType ){
				$this->_( 'post_type', clone $this->_( 'post_type' ) );
			}
			if( $this->_( 'taxonomy' ) instanceof Field_Options_Location_Taxonomy ){
				$this->_( 'taxonomy', clone $this->_( 'taxonomy' ) );
			}
			if( $this->_( 'user' ) instanceof Field_Options_Location_User ){
				$this->_( 'user', clone $this->_( 'user' ) );
			}
			if( $this->_( 'form' ) instanceof Field_Options_Form ){
				$this->_( 'form', clone $this->_( 'form' ) );
			}
			if( $this->_( 'form' ) instanceof Field_Options_Form ){
				$this->_( 'form', clone $this->_( 'form' ) );
			}
		}
		
		
		/**
		 * @param Field_Options $target_Field_Options
		 * @return Field_Options_Location
		 */
		protected function clone_location( Field_Options $target_Field_Options ){
			$new_location = clone $this;
			$new_location->parent_OptionsObject = $target_Field_Options;
			if( $new_location->options() != '' ){
				\register_setting( $new_location->options(), FieldsFactory_Admin::get_field_input_option_name( $target_Field_Options->Field()->ID(), $new_location->options() ) );
			}
			return $new_location;
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
		public function posts( $post_type = null ){
			if( !$this->_( 'post_type' ) instanceof Field_Options_Location_PostType ){
				$this->_( 'post_type', new Field_Options_Location_PostType( $this ) );
				if( !is_null( $post_type ) ) $this->posts()->post_type( $post_type );
				FieldsFactory::$fieldIds_by_locations['post_type'][ $this->getParent_OptionsObject()->Field()->global_ID() ] = $this->getParent_OptionsObject()->Field();
			}
			return $this->_( 'post_type' );
		}
		
		
		/**
		 * @param null|string|string[] $taxonomy
		 * @return Field_Options_Location_Taxonomy
		 */
		public function taxonomies( $taxonomy = null ){
			if( !$this->_( 'taxonomy' ) instanceof Field_Options_Location_Taxonomy ){
				$this->_( 'taxonomy', new Field_Options_Location_Taxonomy( $this ) );
				if( is_string( $taxonomy ) ) $taxonomy = [ $taxonomy ];
				if( is_array( $taxonomy ) ) $this->taxonomies()->taxonomy( $taxonomy );
				FieldsFactory::$fieldIds_by_locations['taxonomy'][ $this->getParent_OptionsObject()->Field()->global_ID() ] = $this->getParent_OptionsObject()->Field();
			}
			return $this->_( 'taxonomy' );
		}
		
		
		/**
		 * @return Field_Options_Location_User
		 */
		public function users(){
			if( !$this->_( 'user' ) instanceof Field_Options_Location_User ){
				$this->_( 'user', new Field_Options_Location_User( $this ) );
				FieldsFactory::$fieldIds_by_locations['user'][ $this->getParent_OptionsObject()->Field()->global_ID() ] = $this->getParent_OptionsObject()->Field();
			}
			return $this->_( 'user' );
		}
		
		
		/**
		 * @param null $page_slug
		 * @return array|Field_Options_Location|mixed|null
		 */
		public function options( $page_slug = null ){
			if( !is_null( $page_slug ) ){
				$this->_( 'options', $page_slug );
				FieldsFactory::$fieldIds_by_locations['options'][ $page_slug ][ $this->getParent_OptionsObject()->Field()->global_ID() ] = $this->getParent_OptionsObject()->Field();
			}
			if( is_string( $page_slug ) && $this->getParent_OptionsObject()->Field()->get_allow_save_field() ){
				\register_setting( $page_slug, FieldsFactory_Admin::get_field_input_option_name( $this->getParent_OptionsObject()->Field()->ID(), $page_slug ) );
			}
			return $this->_( 'options', $page_slug );
		}
		
		
		///ALIAS
		
		
		/**
		 * @param null $page_slug
		 * @return string
		 * @alias $this->Options
		 */
		public function Admin_Menus( $page_slug = null ){
			return $this->options( $page_slug );
		}
		
		
		/**
		 * @param null $post_type
		 * @return Field_Options_Location_PostType
		 */
		protected function Post_Types( $post_type = null ){
			return $this->posts( $post_type );
		}
		
		
	}