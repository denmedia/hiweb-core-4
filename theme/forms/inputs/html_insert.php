<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:06
	 */

	namespace theme\forms\inputs;



	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;
	
	
	class html_insert extends input{

		static $default_name = 'html_insert';
		static $input_title = 'HTML вставка в форму';

		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_script( 'html' ) )->compact( 1 )->flex()->icon('<i class="fad fa-file-code"></i>');
		}


		public function the(){
			echo self::get_data('html', false);
		}


	}