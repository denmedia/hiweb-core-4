<?php

	use theme\recaptcha;


	if( recaptcha::is_enable() ){
		add_action( 'woocommerce_checkout_after_order_review', function(){
			recaptcha::the_input();
		} );
		add_action( 'woocommerce_after_checkout_validation', function( $data, $errors ){
			/** @var WP_Error $errors */
			if( !recaptcha::get_recaptcha_verify() ){
				$errors->add( 'recaptcha', recaptcha::get_error_message() );
			}
		}, 10, 2 );
	}