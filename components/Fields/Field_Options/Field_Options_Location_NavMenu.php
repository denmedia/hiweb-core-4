<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\core\Options\Options;
	
	
	class Field_Options_Location_NavMenu extends Options{
		
		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
		}
		
		
		/**
		 * Set NavMenu depth
		 * @param null $set
		 * @return array|Field_Options_Location_NavMenu|mixed|null
		 */
		public function depth( $set = null ){
			return $this->_( 'depth', $set );
		}
		
		
		/**
		 * Set NavMenu locations
		 * @param null $set
		 * @return array|Field_Options_Location_NavMenu|mixed|null
		 */
		public function locations( $set = null ){
			if(is_string($set) && $set != '') $set = [$set];
			return $this->_( 'locations', $set );
		}
		
	}