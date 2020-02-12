<?php

	namespace hiweb;

	add_action( 'get_footer', 'hiweb\components\console\Messages::the', 9999999999999 );
	add_action( 'wp_footer', 'hiweb\components\console\Messages::the', 9999999999999 );
	add_action( 'admin_footer', 'hiweb\components\console\Messages::the', 9999999999999 );