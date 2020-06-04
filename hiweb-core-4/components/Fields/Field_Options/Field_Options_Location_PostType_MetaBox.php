<?php

	namespace hiweb\components\Fields\Field_Options;


	use hiweb\core\Options\Options;


	class Field_Options_Location_PostType_MetaBox extends Options{


		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
			$this->title( '' );
			$this->Context()->normal();
			$this->Priority()->default_();
		}


		/**
		 * Set meta box title
		 * @param null|string $set
		 * @return array|Field_Options_Location_PostType_MetaBox|mixed|null
		 */
		public function title( $set = null ){
			return $this->_( 'title', $set );
		}


		/**
		 * @return Field_Options_Location_PostType_MetaBox_Context
		 */
		public function Context(){
			if( !$this->_( 'context' ) instanceof Field_Options_Location_PostType_MetaBox_Context ){
				$this->_( 'context', new Field_Options_Location_PostType_MetaBox_Context( $this ) );
			}
			return $this->_( 'context' );
		}


		/**
		 * @return Field_Options_Location_PostType_MetaBox_Priority
		 */
		public function Priority(){
			if( !$this->_( 'priority' ) instanceof Field_Options_Location_PostType_MetaBox_Priority ){
				$this->_( 'priority', new Field_Options_Location_PostType_MetaBox_Priority( $this ) );
			}
			return $this->_( 'priority' );
		}

	}