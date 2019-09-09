<?php

	namespace hiweb\components\fields\options;


	use hiweb\core\strings\Strings;


	class PostType_MetaBox extends \hiweb\core\arrays\Options{

		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
			$this->context()->normal();
			$this->priority()->default();
		}


		/**
		 * Set meta box title
		 * @param null|string $meta_box_title
		 * @return array|PostType_MetaBox|mixed|null
		 */
		public function title( $meta_box_title = null ){
			if( is_string( $meta_box_title ) && $meta_box_title != '' ){
				if( !$this->_is_exists( 'id' ) ) $this->id( $meta_box_title );
			}
			return $this->_( __FUNCTION__, $meta_box_title );
		}


		/**
		 * Set meta box id
		 * @param null|string $meta_box_id
		 * @return array|PostType_MetaBox|mixed|null
		 */
		public function id( $meta_box_id = null ){
			if( is_string( $meta_box_id ) && $meta_box_id != '' ){
				$meta_box_id = Strings::sanitize_id( $meta_box_id );
			}
			return $this->_( 'id', $meta_box_id );
		}


		/**
		 * @return PostType_MetaBox_Context
		 */
		public function context(){
			if( !$this->_is_exists( 'context' ) ){
				$this->set( 'context', new PostType_MetaBox_Context( $this ) );
			}
			return $this->get( 'context' );
		}


		/**
		 * @return PostType_MetaBox_Priority
		 */
		public function priority(){
			if( !$this->_is_exists( 'priority' ) ){
				$this->set( 'priority', new PostType_MetaBox_Priority( $this ) );
			}
			return $this->get( 'priority' );
		}


		/**
		 * @param null $callable - callable function to print meta box html
		 * @return array|PostType_MetaBox|mixed|null
		 */
		public function callback( $callable = null ){
			return $this->_( __FUNCTION__, $callable );
		}


		/**
		 * @param null $callback_args
		 * @return array|PostType_MetaBox|mixed|null
		 */
		public function callback_args( $callback_args = null ){
			return $this->_( __FUNCTION__, $callback_args );
		}


		/**
		 * @return PostType
		 */
		public function _PostType(){
			return $this->getParent_OptionsObject();
		}

	}