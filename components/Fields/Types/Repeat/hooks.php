<?php


	add_action( 'wp_ajax_hiweb-field-repeat-get-row', function(){
//		$field_global_id = \hiweb\urls::request( 'id' );
//		///
//		$R = [ 'result' => false, 'filed-id' => $field_global_id ];
//		//
//		if( !is_string( $field_global_id ) || trim( $field_global_id ) == '' ){
//			$R['error'] = 'Не передан параметр id инпута. Необходимо указать $_POST[id] или $_GET[id].';
//		} else {
//			if( !fields::is_register( $field_global_id ) ){
//				$R['error'] = 'Поле с id[' . $field_global_id . '] не найден!';
//			} else {
//				$R['result'] = true;
//				/** @var fields\types\repeat\field $field */
//				$field = fields::$fields[ $field_global_id ];
//				/** @var fields\types\repeat\input $input */
//				$input = $field->INPUT();
//				$R['data'] = $input->ajax_html_row( \hiweb\urls::request( 'input_name' ), \hiweb\urls::request( 'index' , 0), \hiweb\urls::request( 'values' ) );
//				$R['values'] = $input->ajax_filter_values( \hiweb\urls::request( 'values' ) );
//			}
//		}
//		//
//		echo json_encode( $R, JSON_UNESCAPED_UNICODE );
//		die;
	} );