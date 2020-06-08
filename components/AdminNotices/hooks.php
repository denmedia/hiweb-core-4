<?php
	
	add_action( 'rest_api_init', function(){
		
		register_rest_route( 'hiweb/components', '/adminnotices/is_show', [
			'methods' => 'GET',
			'callback' => function( WP_REST_Request $request ){
				$R = [];
				if( is_array( $_GET['ids'] ) ) foreach( $_GET['ids'] as $id ){
					$R[ $id ] = \hiweb\components\AdminNotices\AdminNotices_Popup_Options::is_show( $id );
				}
				return [ 'popups' => $R ];
			}
		] );
		
		register_rest_route( 'hiweb/components', '/adminnotices/force_close', [
			'methods' => 'GET',
			'callback' => function( WP_REST_Request $request ){
				if( $_GET['id'] != '' ){
					$R = \hiweb\components\AdminNotices\AdminNotices_Popup_Options::set_close_time( $_GET['id'] );
					return [ 'success' => true, 'result' => $R ];
				}
				else{
					return [ 'success' => false, 'message' => 'Не передан Notice ID' ];
				}
			}
		] );
	} );
	
	//Show NOTICES
	add_action( 'admin_notices', '\hiweb\components\AdminNotices\AdminNotices_Factory::_hook_admin_notices' );