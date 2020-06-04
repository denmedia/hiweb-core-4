<?php

	namespace theme\forms\inputs;


	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;
	use hiweb\components\Images\ImagesFactory;
	
	
	class image extends input{

		static $default_name = 'image';
		static $input_title = 'Вставка определенного изображения в форму';


		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_image( 'image' ) )->label( 'Изображение для вставки в форму' )->compact( 1 )->flex()->icon('<i class="fad fa-image-polaroid"></i>');
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'width' )->default_value( 480 ))->label( 'Максимальная ширина' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'height' )->default_value( 360 ) )->label( 'Максимальная высота' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'send_enable' )->label_checkbox( 'Не отправлять данное поле по почте' ) );
		}


		/**
		 *
		 */
		public function the(){
			$image = ImagesFactory::get( self::get_data( 'image' ) );
			if( $image->is_attachment_exists() ){
				$width = intval( self::get_data( 'width' ) );
				$height = intval( self::get_data( 'height' ) );
				if( $width < 8 ) $width = 8;
				if( $height < 8 ) $height = 8;
				?>
				<div class="input-image-wrap">
					<?php
						echo $image->html( [ $width, $height ], - 1 );
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


	}