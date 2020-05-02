<?php

	namespace hiweb\components\Fields;


	use hiweb\components\Fields\Field_Options\Field_Options_Location;
	use hiweb\core\Options\Options;


	class Field_Options extends Options{

		/** @var Field_Options_Location */
		protected static $the_last_location;

		/** @var Field */
		protected $Field;


		public function __construct( Field $Field ){
			parent::__construct();
			$this->Field = $Field;
		}


		/**
		 * @return Field
		 */
		public function Field(){
			return $this->Field;
		}


		/**
		 * Set field label
		 * @param null|string $set
		 * @return $this|string
		 */
		public function label( $set = null ){
			return $this->_( 'label', $set );
		}


		/**
		 * Set field description
		 * @param null|string $set
		 * @return $this|string
		 */
		public function description( $set = null ){
			return $this->_( 'description', $set );
		}


		/**
		 * Get / set default value
		 * @param null $set
		 * @return array|Field_Options|mixed|null
		 */
		public function default_value( $set = null ){
			return $this->_( 'default_value', $set );
		}


		/**
		 * Set order position in form
		 * @param null|int $set
		 * @return array|Field_Options|mixed|null
		 */
		public function position( $set = null ){
			return $this->_( 'position', $set );
		}


		/**
		 * Set field location
		 * @param bool $repeat_last_location - put this
		 * @return Field_Options_Location
		 */
		public function Location( $repeat_last_location = false ){
			if( $repeat_last_location && self::$the_last_location != $this->_( 'location' ) && self::$the_last_location instanceof Field_Options_Location ){
				if( $this->_( 'location' ) instanceof Field_Options_Location ){
					$this->_( 'location' )->_set_optionsCollect( self::$the_last_location->_get_optionsCollect() );
				} else {
					$this->_( 'location', clone self::$the_last_location );
				}
			} else {
				if( !$this->_( 'location' ) instanceof Field_Options_Location ){
					$this->_( 'location', new Field_Options_Location( $this ) );
				}
			}
			self::$the_last_location = $this->_( 'location' );
			return $this->_( 'location' );
		}
		
		
		///DEPRECATED
		
		
		/**
		 * @alias $this->default_value()
		 * @deprecated
		 * @param null $set
		 * @return array|Field_Options|mixed|null
		 */
		protected function value($set = null){
			return $this->default_value($set);
		}
		
		
		/**
		 * @deprecated
		 */
		protected function get_parent_field(){
			return $this;
		}
		
		
		/**
		 * @deprecated
		 * @return Field_Options\Field_Options_Location_Form
		 */
		protected function Form(){
			return $this->Location()->Form();
		}

	}