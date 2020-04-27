<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsAdminFactory;
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
			if(!is_null($page_slug)) {
				$this->_( 'options', $page_slug );
			}
			if( $this->getParent_OptionsObject()->Field()->get_allow_save_field() ){
				\register_setting( $page_slug, FieldsAdminFactory::get_field_input_option_name($this->getParent_OptionsObject()->Field()) );
			}
			return $this->_( 'options', $page_slug );
		}
		
		
		/**
		 * @param null $set
		 */
		public function order( $set = null ){
			$this->_( 'order', $set, 10 );
		}
		
		
	}