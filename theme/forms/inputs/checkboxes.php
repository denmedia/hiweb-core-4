<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 16.10.2018
	 * Time: 19:03
	 */

	namespace theme\forms\inputs;


	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;
	use hiweb\core\ArrayObject\ArrayObject;
	
	
	class checkboxes extends input{

		static $input_title = 'Чекбоксы';


		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Опция' )->default_value( 'Опция' ) )->label( 'Чекбоксы (галочки)' )->compact( 1 )->flex()->icon('<i class="fad fa-check-double"></i>');
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_textarea( 'variants' )->placeholder( 'Варианты, на каждой новой строчке' ) )->label( 'Варианты' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'require-min' )->label( 'Обязательный минимум' ) )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'require-message' )->label( 'Отметьте минимум один пункт' )->default_value( 'Вы не верно заполнили поле' ) )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'send_enable' )->label_checkbox( 'Не отправлять данное поле по почте' ) )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'sendpusle_append' )->label_checkbox( 'Отправлять данные в SendPulse' )->description( 'Отметьте этот пункт, если хотите отправлять эти данные в SendPulse. Индификатор поля важен и будет назначен ключем для данных.' ) );
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
			$items = self::get_data( 'variants' );
			if( trim( $items ) == '' ) return;
			?>
			<div class="form-input-field-title"><?= self::get_data( 'label' ) ?>  <?= $this->is_required() ? '<span class="required">*</span>' : '' ?></div>
			<div data-form-field-checkboxes-min="<?= intval( self::get_data( 'require-min' ) ) ?>">
				<?php
					foreach( explode( "\n", $items ) as $item ){
						if( $item == '' ) continue;
						?>
						<div class="form-input-field-item">
							<label>
								<input type="checkbox" name="<?= self::get_name() ?>[]" value="<?= htmlentities( trim( $item ) ) ?>"/>
								<?= $item ?>
							</label>
						</div>
						<?php
					}
				?>
			</div>
			<?php
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
			return self::get_data( 'send_enable' ) != 'on';
		}


		/**
		 * @param $value
		 * @return array|string
		 */
		public function get_email_value( $value ){
			return is_array( $value ) ? join( ', ', $value ) : '-';
		}
		
		
		/**
		 * @param string $submit_value
		 * @return bool
		 */
		public function is_required_validate( $submit_value = '' ){
			return ( ( is_array( $submit_value ) && count( $submit_value ) < intval( ArrayObject::get_instance( $submit_value )->_( 'require-min' ) ) ) || ( is_string( $submit_value ) && trim( $submit_value ) == '' ) );
		}
		
		
	}