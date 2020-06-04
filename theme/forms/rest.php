<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 16:15
	 */

	use hiweb\urls;
	use theme\forms;


	add_action( 'rest_api_init', function(){
		register_rest_route( 'hiweb_theme', 'forms/submit', [
			'methods' => 'post',
			'callback' => function(){
				return forms::get( $_POST['hiweb-theme-widget-form-id'] )->do_submit( $_POST );
			}
		] );

		register_rest_route( 'hiweb_theme', 'forms/input_html', [
			'methods' => 'post',
			'callback' => function(){
				if( is_null( PathsFactory::request( 'form_id' ) ) ) return [ 'success' => false, 'message' => 'найден ID формы [form_id="..."]' ];
				$input_id = null;
				$input_value = null;
				if( isset( $_POST['data'] ) && is_array( $_POST['data'] ) ){
					foreach( $_POST['data'] as $subdata ){
						$input_id = $subdata['name'];
						$input_value = $subdata['value'];
					}
				}
				forms::init();
				$form = get_form( PathsFactory::request( 'form_id' ) );
				if( !$form->is_exists() ) wp_send_json_error( [ 'success' => false, 'message' => 'форма не найдена' ] );
				forms::setup_postdata( $form->get_id() );
				if( !$form->is_input_exists( $input_id ) ) return [ 'success' => false, 'message' => 'инпут ID не найден 1' ];
				$input = $form->get_input_object( $input_id );
				$form->setup_input_id( $input_id );
				if( $input instanceof forms\inputs\input ){
					$input->set_data( 'value', $input_value );
					return $input->ajax_html();
				} else {
					wp_send_json_error( [ 'message' => 'Непредвиденная ошибка' ] );
				}
				wp_send_json_error();
			}
		] );

		register_rest_route( 'hiweb_theme', 'forms/messages', [
			'methods' => 'get',
			'callback' => function(){
				if( !get_field( 'messages-rest-enable', forms::$post_type_name ) ) wp_send_json_error( [ 'message' => 'Сбор данных сообщений из форм выключен в настройках' ] );
				if( !isset( $_GET['key'] ) && !isset( $_POST['key'] ) ) wp_send_json_error( [ 'message' => 'Ключ "key" для получения сообщений не передан' ] );
				if( !in_array( get_field( 'messages-rest-key', forms::$post_type_name ), [ $_GET['key'], $_POST['key'] ] ) ) wp_send_json_error( [ 'message' => 'Ключ key не совпадает' ] );
				$messages_args = [
					'post_type' => forms::$post_type_messages_name,
					'posts_per_page' => get_field( 'messages-rest-limit', forms::$post_type_name )
				];
				if( get_field( 'messages-rest-hide-showed', forms::$post_type_name ) ){
					$messages_args['meta_query'] = [
						'relation' => 'OR',
						[
							'key' => 'message-rest-exported',
							'compare' => 'NOT EXISTS'
						],
						[
							'key' => 'message-rest-exported',
							'value' => '0',
							'compare' => '!='
						]
					];
				}
				$messages = ( new WP_Query( $messages_args ) )->get_posts();
				if( !is_array( $messages ) || count( $messages ) == 0 ){
					wp_send_json_error( [ 'message' => 'Нет новых сообщений' ] );
				} else {
					$R = [];
					$convert_keys = apply_filters( 'rest/hiweb_theme/forms/messages-convert_keys', [], get_defined_vars() );
					//$keys_unset = apply_filters( 'rest/hiweb_theme/forms/messages-keys_unset', [], get_defined_vars() );
					$allowed_keys = apply_filters( 'rest/hiweb_theme/forms/messages-allowed_keys', [], get_defined_vars() );
					/** @var WP_Post $message */
					foreach( $messages as $index => $message ){
						$R[ $index ] = [
							'site' => \hiweb\PathsFactory::get()->domain(),
							'post_date' => get_the_date( 'Y:m:d H:i:s', $message ),
							'post_content' => strip_tags( $message->post_content )
						];
						update_post_meta( $message->ID, 'message-rest-exported', '0' );
						$form_meta_data = get_post_meta( $message->ID, 'form-data-post', true );
						if( is_array( $form_meta_data ) ) foreach( $form_meta_data as $key => $value ){
							$R[ $index ][ $key ] = $value;
						}
						if( !forms::get_utm_points_options()->is_empty() ){
							$utm_points = get_post_meta( $message->ID, 'utm-points', true );
							$R[ $index ]['UTM'] = [];
							foreach( forms::get_utm_points_options()->get() as $point ){
								if( isset( $utm_points[ $point ] ) ){
									$R[ $index ]['UTM'][ $point ] = $utm_points[ $point ];
								} else {
									$R[ $index ]['UTM'][ $point ] = '';
								}
							}
						}
						if( is_array( $convert_keys ) ) foreach( $convert_keys as $key_source => $key_dest ){
							if( array_key_exists( $key_source, $R[ $index ] ) ){
								$R[ $index ][ $key_dest ] = $R[ $index ][ $key_source ];
								unset( $R[ $index ][ $key_source ] );
							}
						}
						if( is_array( $allowed_keys ) && count( $allowed_keys ) > 0 ){
							foreach( $R[ $index ] as $key => $value ){
								if( !in_array( $key, $allowed_keys ) ) unset( $R[ $index ][ $key ] );
							}
							foreach( $allowed_keys as $key ){
								if( !array_key_exists( $key, $R[ $index ] ) ) $R[ $index ][ $key ] = '';
							}
						}
					}
					wp_send_json( $R );
				}
			}
		] );
	} );