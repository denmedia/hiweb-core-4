<?php

	namespace hiweb\components\Fields\Field_Options;


	use hiweb\core\Options\Options;


	class Field_Options_Location extends Options{

		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
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


	}