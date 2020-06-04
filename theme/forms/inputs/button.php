<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 19:02
	 */

	namespace theme\forms\inputs;


	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;
	
	
	class button extends input{
	
			static $input_title = 'Кнопка';
	
	
			static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
				$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Отправить' )->default_value( 'Отправить' ) )->label( 'Кнопка с текстом' )->compact( 1 )->flex()->icon('<i class="fas fa-envelope-square"></i>');
				$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_select( 'type' )->options( [ 'submit' => 'Отправка формы', 'reset' => 'Сброс формы' ] ) );
				$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_fontawesome( 'icon' )->label('Иконка для поля') );
			}
	
			public function the_prefix(){
				?>
				<div class="input-wrap input-wrap-button-<?= self::get_data( 'type' ) ?>">
				<?php
			}
	
			public function the(){
				$this->the_prefix();
				?>
				<button type="<?= self::get_data( 'type' ) ?>" data-form-object-id="<?=get_the_form()->get_object_id()?>" <?= self::get_data( 'type' ) == 'submit' ? 'disabled' : '' ?>>
				<?php
	if( isset( $this->data['icon'] ) && $this->data['icon'] != '' ){
					?>
					<i class="<?= $this->data['icon'] ?>"></i>
					<?php
				}
	 ?>
				<?= self::get_data( 'label' ) ?>
				</button>
				<?php
				$this->the_sufix();
			}
	
	
		}