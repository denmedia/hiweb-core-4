<?php

	namespace theme\forms\inputs;


	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;


	class listing extends input{

		static $default_name = 'info_text';
		static $input_title = 'Список значений и свойств';


		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			$repeat = add_field_repeat( 'items' )->label_button_new_row('Добавить элемент');
			$repeat->add_col_field( add_field_text( 'key' ) )->label( 'Ключ списка' );
			$repeat->add_col_field( add_field_text( 'value' ) )->label( 'Значение списка' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, $repeat )->label( 'Список значений (свойств)' )->compact( 1 )->flex()->icon('<i class="fad fa-list-alt"></i>');
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'send_enable' )->label_checkbox( 'Не отправлять данное поле по почте' ) )->compact(1);

		}


		public function is_required(){
			return false;
		}


		public function the(){
			$this->the_prefix();
			$items = get_array( self::get_data( 'items', false ) );
			if( !$items->is_empty() ){
				?>
				<ul>
					<?php
						foreach( $items->get() as $data ){
							$item = get_array( $data );
							?>
							<li><span class="item-key"><?= $item->get_value( 'key' ) ?>:</span> <span class="item-value"><?= $item->get_value( 'value' ) ?></span></li>
							<?php
						}
					?>
				</ul>
				<?php
			}
			$this->the_sufix();
		}


		/**
		 * @return bool
		 */
		public function is_email_submit_enable(){
			return self::get_data( 'send_enable' ) != 'on';
		}


		public function get_email_html( $value ){
			ob_start();
			$this->the();
			return ob_get_clean();
		}


	}