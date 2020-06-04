<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:06
	 */

	namespace theme\forms\inputs;


	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;


	class number extends input{

		static $default_name = 'number';
		static $input_title = 'Цифровое поле';

		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' ) )->label( 'Цифровое поле' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'placeholder' )->placeholder( 'Плейсхолдер в поле' ) )->label( 'Плейсхолдер в поле' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text('min') )->label('Минимальное значение')->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text('max') )->label('Максимальное значение')->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) );

		}


		public function the(){
			$this->the_prefix();
			?><input type="number" <?=self::get_data('min') != '' ? 'min="'.self::get_data('min').'"' : ''?> <?=self::get_data('max') != '' ? 'max="'.self::get_data('max').'"' : ''?> <?=self::get_data('min') != '' ? 'value="'.self::get_data('min').'"' : '' ?> name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?>/>
			<?php
			$this->the_sufix();
		}
		
		
		/**
		 * @param string $submit_value
		 * @return bool
		 */
		public function is_required_validate( $submit_value = '' ){
			return strlen( $submit_value ) < 1 ;
		}
		
	}