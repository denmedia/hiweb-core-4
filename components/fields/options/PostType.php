<?php

	namespace hiweb\components\fields\options;


	use hiweb\components\fields\Field;
	use hiweb\core\ArrayObject\Arrays;
	use hiweb\core\Strings;


	class PostType extends \hiweb\core\ArrayObject\Options{

		public function __construct( $parent_OptionsObject ){
			parent::__construct( $parent_OptionsObject );
			$this->priority( 10 );
			$this->MetaBox( 'hiweb-meta-box-default' );
		}


		/**
		 * Set post type for show admin field in post edit screen
		 * @param null $post_types
		 * @return array|PostType|mixed|null
		 */
		public function post_type( $post_types = null ){
			if( is_string( $post_types ) && $post_types != '' ) $post_types = (array)$post_types;
			return $this->_( 'post-type', $post_types );
		}


		/**
		 * @param null $priority - default is 10
		 * @return array|PostType|mixed|null
		 */
		public function priority( $priority = null ){
			return $this->_( __FUNCTION__, $priority );
		}


		/**
		 * @param null $label
		 * @return array|PostType|mixed|null
		 */
		public function label( $label = null ){
			return $this->_( __FUNCTION__, $label );
		}


		/**
		 * @param null $description
		 * @return array|PostType|mixed|null
		 */
		public function description( $description = null ){
			return $this->_( __FUNCTION__, $description );
		}


		/**
		 * @param string $idOrTitle
		 * @return PostType_MetaBox
		 */
		public function MetaBox( $idOrTitle = '' ){
			if( !$this->_is_exists( 'meta_boxes' ) ){
				$this->_( 'meta_boxes', new PostType_MetaBox( $this ) );
				$this->get( 'meta_boxes' )->title( $idOrTitle );
			}
			$this->remove( 'position' );
			return $this->_( 'meta_boxes' );
		}


		/**
		 * @param null $column_label
		 * @return PostType_ManageColumns
		 */
		public function ManageColumns( $column_label = null ){
			if( !$this->_is_exists( 'manage_columns' ) ){
				$this->set( 'manage_columns', new PostType_ManageColumns( $this ) );
				$this->get( 'manage_columns' )->label( $column_label );
			}
			return $this->get( 'manage_columns' );
		}


		/**
		 * @return PostType_Position
		 */
		public function PositionByHook(){
			if( !$this->_is_exists( 'position' ) ){
				$this->set( 'position', new PostType_Position( $this ) );
			}
			foreach( $this->Arrays()->get() as $key => $value ){
				if( preg_match( '/(^meta_box($|:))/i', $key ) > 0 ){
					$this->remove( $key );
				}
			}
			return $this->get( 'position' );
		}


		/**
		 * @param string $idOrTitle
		 * @return PostType_MetaBox
		 * @deprecated use ->MetaBox(...)->...
		 */
		public function META_BOX( $idOrTitle = '' ){
			return $this->MetaBox( $idOrTitle );
		}


		/**
		 * @return PostType_Position
		 * @deprecated use ->PositionByHook()->...
		 */
		public function POSITION(){
			return $this->PositionByHook();
		}

	}