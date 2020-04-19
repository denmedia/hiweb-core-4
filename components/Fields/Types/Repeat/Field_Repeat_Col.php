<?php
	
	namespace hiweb\components\Fields\Types\Repeat;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\components\Fields\Field_Options;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\core\Options\Options;
	
	
	class Field_Repeat_Col extends Options{
		
		/** @var Field */
		private $Field;
		/** @var Field */
		private $repeat_Field;
		
		
		/**
		 * @param Field|Field_Options $Field_or_FieldOptions
		 * @param Field               $repeat_Field
		 */
		public function __construct( Field $repeat_Field, $Field_or_FieldOptions ){
			$this->repeat_Field = $repeat_Field;
			if( $Field_or_FieldOptions instanceof Field ){
				$this->Field = $Field_or_FieldOptions;
			}
			elseif( $Field_or_FieldOptions instanceof Field_Options ){
				$this->Field = $Field_or_FieldOptions->Field();
			}
			parent::__construct( $this->Field->Options() );
		}
		
		
		/**
		 * @return bool
		 */
		protected function is_exist(){
			return $this->Field instanceof Field;
		}
		
		
		/**
		 * @return string
		 */
		public function get_id(){
			return $this->is_exist() ? $this->Field->ID() : null;
		}
		
		
		/**
		 * @alias $this->get_id()
		 * @return string
		 */
		public function ID(){
			return $this->get_id();
		}
		
		
		public function Field(){
			return $this->is_exist() ? $this->Field : FieldsFactory::get_field( '' );
		}
		
		
		/**
		 * @param null|string $set
		 * @return array|Field_Repeat_Col|mixed|null
		 */
		public function label( $set = null ){
			return $this->_( 'label', $set );
		}
		
		
		/**
		 * @param null|string $set
		 * @return array|Field_Repeat_Col|mixed|null
		 */
		public function description( $set = null ){
			return $this->_( 'description', $set );
		}
		
		
		/**
		 * @param null|int $set
		 * @return array|Field_Repeat_Col|mixed|null
		 */
		public function width( $set = null ){
			return $this->_( 'width', $set );
		}
		
		
		/**
		 * Set col compact mod
		 * @param null|bool|int $set
		 * @return array|Field_Repeat_Col|mixed|null
		 */
		public function compact( $set = null ){
			return $this->_( 'compact', $set );
		}
		
	}