<?php

	namespace hiweb\components\fields\options;


	class PostType_ManageColumns extends \hiweb\core\ArrayObject\Options{


		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
			$this->position( 3 );
			$this->label( $this->getParent_OptionsObject()->get( 'label' ) );
		}


		/**
		 * @param null $position
		 * @return array|PostType_ManageColumns|mixed|null
		 */
		public function position( $position = null ){
			return $this->_( __FUNCTION__, $position );
		}


		/**
		 * @param null $column_label
		 * @return array|PostType_ManageColumns|mixed|null
		 */
		public function label( $column_label = null ){
			return $this->_( __FUNCTION__, $column_label );
		}


		/**
		 * @alias ::column_name(...)
		 * @param null $column_label
		 * @return array|PostType_ManageColumns|mixed|null
		 */
		public function name( $column_label = null ){
			return $this->label( $column_label );
		}


		/**
		 * @param null $callable
		 * @return array|PostType_ManageColumns|mixed|null
		 */
		public function callback( $callable = null ){
			return $this->_( __FUNCTION__, $callable );
		}

	}