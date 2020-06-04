<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:51
	 */

	namespace theme\forms\inputs;


	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;


	class phone extends input{

		static $default_name = 'phone';
		static $input_title = 'Номер телефона';


		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' ) )->label( 'Номер телефона' )->compact( 1 )->flex()->icon('<i class="fad fa-phone-square"></i>');
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'placeholder' )->placeholder( 'Плейсхолдер в поле' ) )->label( 'Плейсхолдер в поле' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'mask-use' )->label_checkbox( 'Использовать маску' ) )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'mask' )->placeholder( '+7(999)999-99-99' )->default_value( '+7(999)999-99-99' ) )->label('Макска для ввода телефона')->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'require-message' )->label( 'Сообщение под полем при неверно заполненом поле' )->default_value('Вы не верно указали номер телефона') )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_fontawesome( 'icon' ) )->label('Иконка для поля');
		}

		public function the(){
			$this->the_prefix();
			?>
			<div class="input"><input type="text" autocomplete="off" <?=$this->get_tag_pair('data-input-mask','mask')?> name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?> <?=self::is_required() ? 'data-required' : ''?> /></div>
			<?php
			$this->the_sufix();
		}


	}