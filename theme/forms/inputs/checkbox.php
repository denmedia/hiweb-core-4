<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 16.10.2018
	 * Time: 19:03
	 */

	namespace theme\forms\inputs;
	


	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;
	
	
	class checkbox extends input{

		static $input_title = 'Чекбокс';


		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Опция' )->default_value( 'Опция' ))->label( 'Чекбокс (галочка)' )->compact( 1 )->flex()->icon('<i class="fad fa-check-square"></i>');
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'require-message' )->label( 'Отметьте минимум один пункт' )->default_value('Вы не верно заполнили поле') )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'send_enable' )->label_checkbox( 'Не отправлять данное поле по почте' ) );
		}

		public function the_prefix(){
			?>
			<div class="input-wrap input-wrap-<?= self::get_name() ?>">
			<?php if( self::get_data( 'label' ) != '' ){
				?>

				<?php
			} elseif( self::is_required_empty_label() ) {
				?>
				<div class="required-empty-label">
				<?php
			}
		}

		public function the(){
			$this->the_prefix();
			if( self::get_data( 'label' ) != '' ){
				?>
				<label class="label">
				<?php
			}
			?>
			<input type="checkbox" name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?> <?=self::is_required() ? 'data-required' : ''?>/>
			<?php
			if( self::get_data( 'label' ) != '' ){
				?>
				<?= self::get_data( 'label', false ) ?> <?= $this->is_required() ? '<span class="required">*</span>' : '' ?></label>
				<?php
			}
			$this->the_sufix();
		}

		public function the_sufix(){
			?>
			<?= self::get_require_error_message_html() ?>
			</div>
			<?php
			if( self::is_required_empty_label() ){
				?>
				</div>
				<?php
			}
		}


		/**
		 * @return bool
		 */
		public function is_email_submit_enable(){
			return self::get_data('send_enable') != 'on';
		}


	}