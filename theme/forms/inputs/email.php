<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:23
	 */

	namespace theme\forms\inputs;


	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;


	class email extends input{

		static $default_name = 'email';
		static $input_title = 'Адрес почты';


		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' ) )->label( 'email' )->compact( 1 )->flex()->icon('<i class="far fa-at"></i>');
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'placeholder' )->placeholder( 'Плейсхолдер в поле' ) )->label( 'Плейсхолдер в поле' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'require-message' )->label( 'Сообщение под полем при неверно заполненом поле' )->default_value('Вы не верно указали адрес email') )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_fontawesome( 'icon' )->label('Иконка для поля') );
		}

		public function the(){
			$this->the_prefix();
			?>
			<div class="input"><input type="email" name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?> <?=self::is_required() ? 'data-required' : ''?>/></div>
			<?php
			$this->the_sufix();
		}
		
		
		/**
		 * @param string $submit_value
		 * @return bool|mixed
		 */
		public function is_required_validate( $submit_value = '' ){
			return filter_var( $submit_value, FILTER_VALIDATE_EMAIL );
		}
		
		
		/**
		 * @return bool
		 */
		public function is_type_email(){
			return true;
		}
		
		
	}