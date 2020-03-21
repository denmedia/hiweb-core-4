<?php

	namespace hiweb\components\fields\types\text;


	use hiweb\components\fields\Field;
	use hiweb\components\fields\options\Options;


	class Field_Text extends Field{

		/**
		 * @return Options|Field_Text_Options
		 */
		public function Options(){
			if( !$this->Options instanceof Field_Text_Options ) $this->Options = new Field_Text_Options( null, $this );
			return $this->Options;
		}


		/**
		 * Print field input
		 * @param $name
		 * @param $value
		 */
		public function the_form_input( $name, $value ){
			if(is_null($value)) $value = $this->Options()->_('default_value');
			?>
			<input name="<?= esc_attr( $name ) ?>" value="<?= esc_attr( $value ) ?>" placeholder="<?= esc_attr( $this->Options()->placeholder() ) ?>"/>
			<?php
		}


	}