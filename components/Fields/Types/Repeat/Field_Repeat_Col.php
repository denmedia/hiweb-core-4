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
		/** @var Field_Repeat_Flex */
		private $Flex;
		
		
		/**
		 * @version 1.1
		 * @param Field               $repeat_Field
		 * @param Field|Field_Options $Field_or_FieldOptions
		 * @param null                $Flex
		 */
		public function __construct( Field $repeat_Field, $Field_or_FieldOptions, $Flex = null ){
			$this->repeat_Field = $repeat_Field;
			if( $Flex instanceof Field_Repeat_Flex ) $this->Flex = $Flex;
			else $this->Flex = new Field_Repeat_Flex( $this->Flex, '' );
			if( $Field_or_FieldOptions instanceof Field ){
				$this->Field = $Field_or_FieldOptions;
			}
			elseif( $Field_or_FieldOptions instanceof Field_Options ){
				$this->Field = $Field_or_FieldOptions->field();
			} else {
				$this->Field = FieldsFactory::get_field('');
			}
			parent::__construct( $this->Field->options() );
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
			return $this->is_exist() ? $this->Field->id() : null;
		}
		
		
		/**
		 * @alias $this->get_id()
		 * @return string
		 */
		public function ID(){
			return $this->get_id();
		}
		
		
		/**
		 * @return Field|Field_Options
		 */
		public function field(){
			return $this->is_exist() ? $this->Field : FieldsFactory::get_field( '' );
		}
		
		
		/**
		 * @return Field_Repeat_Flex
		 */
		public function flex(){
			return $this->Flex;
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
			return $this->_( 'width', $set, 1 );
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