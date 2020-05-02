<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\core\Options\Options;
	
	
	class Field_Options_Location_PostType extends Options{
		
		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
			$this->Position()->edit_form_after_editor();
		}
		
		
		/**
		 * @param int|string|int[]|string[] $set
		 * @return $this
		 */
		public function ID( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param null|string $set
		 * @return $this
		 */
		public function post_type( $set = null ){
			if( is_string( $set ) ) $set = [ $set ];
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param string|string[] $set
		 * @return $this
		 */
		public function post_name( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param string|string[] $set
		 * @return $this
		 */
		public function post_status( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param bool $set
		 * @return $this
		 */
		public function comment_status( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param string|string[] $set
		 * @return $this
		 */
		public function post_parent( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param string|string[] $set
		 * @return $this
		 */
		public function has_taxonomy( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param bool $set
		 * @return $this
		 */
		public function front_page( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param bool $set
		 * @return $this
		 */
		public function home_page( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function template( $set = null ){
			return $this->_( 'template', $set );
		}
		
		
		/**
		 * @return Field_Options_Location_PostType_Position
		 */
		public function Position(){
			if( !$this->_( 'position' ) instanceof Field_Options_Location_PostType_Position ){
				$this->_( 'position', new Field_Options_Location_PostType_Position( $this ) );
			}
			return $this->_( 'position' );
		}
		
		
		/**
		 * @param null|string $set_title
		 * @return Field_Options_Location_PostType_MetaBox
		 */
		public function MetaBox( $set_title = null ){
			if( !$this->_( 'metabox' ) instanceof Field_Options_Location_PostType_MetaBox ){
				$this->_( 'metabox', new Field_Options_Location_PostType_MetaBox( $this ) );
			}
			$this->Position()->clear();
			if( !is_null( $set_title ) ) $this->_( 'metabox' )->title( $set_title );
			return $this->_( 'metabox' );
		}
		
		
		/**
		 * @return Field_Options_Location_PostType_ColumnsManager
		 */
		public function ColumnsManager(){
			if( !$this->_( 'columns_manager' ) instanceof Field_Options_Location_PostType_ColumnsManager ){
				$this->_( 'columns_manager', new Field_Options_Location_PostType_ColumnsManager( $this ) );
			}
			return $this->_( 'columns_manager' );
		}
		
		
		/**
		 * @deprecated
		 * @return Field_Options_Location_PostType_ColumnsManager
		 */
		public function Columns_Manager(){
			return $this->ColumnsManager();
		}
		
		
		/**
		 * @param null $title
		 * @return Field_Options_Location_PostType_MetaBox
		 */
		public function Meta_Box($title = null){
			return $this->MetaBox($title);
		}
		
	}