<?php
	
	namespace hiweb\components\Fields\Types\Repeat;
	
	
	use hiweb\core\Options\Options;
	
	
	class Field_Repeat_Flex extends Options{
		
		private $Field_Repeat;
		private $ID;
		
		
		public function __construct( Field_Repeat $Field_Repeat, $ID = '' ){
			parent::__construct();
			$this->Field_Repeat = $Field_Repeat;
			$this->ID = (string)$ID;
		}
		
		
		public function ID(){
			return $this->ID;
		}
		
		
		/**
		 * @param null   $set
		 * @return array|Field_Repeat_Flex|mixed|null
		 */
		public function label( $set = null ){
			return $this->_( 'label', $set, $this->Field_Repeat->Options()->label_button_new_row() );
		}
		
		
		/**
		 * Set font awesome
		 * @param null   $set
		 * @param string $default
		 * @return array|Field_Repeat_Flex|mixed|null
		 */
		public function icon( $set = null, $default = 'fad fa-layer-plus' ){
			return $this->_( 'icon', $set, $default );
		}
		
	}