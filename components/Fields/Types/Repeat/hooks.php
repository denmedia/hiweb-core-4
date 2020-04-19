<?php
	
	use hiweb\components\Fields\Types\Repeat\Field_Repeat;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Row;
	
	
	add_action( 'wp_ajax_hiweb-field-repeat-get-row', function(){
		$field_global_id = $_POST['id'];
		///
		$R = [ 'result' => false, 'filed-id' => $field_global_id ];
		//
		if( !is_string( $field_global_id ) || trim( $field_global_id ) == '' ){
			$R['error'] = 'Не передан параметр id инпута. Необходимо указать $_POST[id] или $_GET[id].';
		}
		else{
			/** @var Field_Repeat $Field */
			$Field = \hiweb\components\Fields\FieldsFactory::get_field( $field_global_id );
			if( $Field->get_ID() == '' ){
				$R['error'] = 'Поле с id[' . $field_global_id . '] не найден!';
			}
			else{
				$cols = $Field->Options()->get_cols();
				$R['cols'] = $cols;
				if(!array_key_exists( $_POST['flex_row_id'], $Field->get_flexes() )) {
					$R['result'] = false;
					$R['error'] = 'Данного flex id нет';
				} else {
					$R['result'] = true;
					ob_start();
					( new Field_Repeat_Row( $Field, 10, $cols[$_POST['flex_row_id']], ['_flex_row_id' => $_POST['flex_row_id']] ) )->the();
					$R['data'] = ob_get_clean();
				}
				//$Field_Repeat_Value = $Field->Value( $_POST['values'] );
				
				//$R['data'] = $Field->ajax_html_row( \hiweb\urls::request( 'input_name' ), \hiweb\urls::request( 'index' , 0), \hiweb\urls::request( 'values' ) );
				//$R['values'] = $input->ajax_filter_values( \hiweb\urls::request( 'values' ) );
			}
		}
		//
		echo json_encode( $R, JSON_UNESCAPED_UNICODE );
		die;
	} );