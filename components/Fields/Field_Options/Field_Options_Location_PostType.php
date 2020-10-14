<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\core\Options\Options;
	
	
	class Field_Options_Location_PostType extends Options{
		
		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
			$this->position()->edit_form_after_editor();
		}
		
		
		/**
		 * @param int|string|int[]|string[] $set
		 * @return $this
		 */
		public function id( $set = null ){
			return $this->_( __FUNCTION__, $set );
		}
		
		
		/**
		 * @param null|string $set
		 * @return $this
		 */
		public function post_type( $set = null ){
			if( is_string( $set ) ) $set = [ $set ];
			return $this->_( '|' . __FUNCTION__, $set );
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
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function woocommerce_shop_page( $set = null ){
			return $this->_( 'woocommerce_shop_page', $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function woocommerce_cart_page( $set = null ){
			return $this->_( 'woocommerce_cart_page', $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function woocommerce_checkout_page( $set = null ){
			return $this->_( 'woocommerce_checkout_page', $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function woocommerce_pay_page( $set = null ){
			return $this->_( 'woocommerce_pay_page', $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function woocommerce_thanks_page( $set = null ){
			return $this->_( 'woocommerce_thanks_page', $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function woocommerce_myaccount_page( $set = null ){
			return $this->_( 'woocommerce_myaccount_page', $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function woocommerce_edit_address_page( $set = null ){
			return $this->_( 'woocommerce_edit_address_page', $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function woocommerce_view_order_page( $set = null ){
			return $this->_( 'woocommerce_view_order_page', $set );
		}
		
		
		/**
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function woocommerce_terms_page( $set = null ){
			return $this->_( 'woocommerce_terms_page', $set );
		}
		
		
		/**
		 * Disable Gutenberg editor on this post edit page
		 * @param $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function disable_gutenberg( $set = null ){
			return $this->_( 'disable_gutenberg', $set );
		}
		
		
		/**
		 * Disable gutenberg and default editor
		 * @param null $set
		 * @return array|Field_Options_Location_PostType|mixed|null
		 */
		public function disable_editor( $set = null ){
			if($set == true) $this->disable_gutenberg( true );
			return $this->_( 'disable_editor', $set );
		}
		
		
		/**
		 * @return Field_Options_Location_PostType_Position
		 */
		public function position(){
			if( !$this->_( 'position' ) instanceof Field_Options_Location_PostType_Position ){
				$this->_( 'position', new Field_Options_Location_PostType_Position( $this ) );
			}
			return $this->_( 'position' );
		}
		
		
		/**
		 * @param null|string $set_title
		 * @return Field_Options_Location_PostType_MetaBox
		 */
		public function metaBox( $set_title = null ){
			if( !$this->_( 'metabox' ) instanceof Field_Options_Location_PostType_MetaBox ){
				$this->_( 'metabox', new Field_Options_Location_PostType_MetaBox( $this ) );
			}
			$this->position()->clear();
			if( !is_null( $set_title ) ) $this->_( 'metabox' )->title( $set_title );
			return $this->_( 'metabox' );
		}
		
		
		/**
		 * @return Field_Options_Location_PostType_ColumnsManager
		 */
		public function columnsManager(){
			if( !$this->_( 'columns_manager' ) instanceof Field_Options_Location_PostType_ColumnsManager ){
				$this->_( 'columns_manager', new Field_Options_Location_PostType_ColumnsManager( $this ) );
			}
			return $this->_( 'columns_manager' );
		}
		
		
		/**
		 * @return Field_Options_Location_PostType_ColumnsManager
		 * @deprecated
		 */
		public function Columns_Manager(){
			return $this->columnsManager();
		}
		
		
		/**
		 * @param null $title
		 * @return Field_Options_Location_PostType_MetaBox
		 */
		public function Meta_Box( $title = null ){
			return $this->metaBox( $title );
		}
		
	}