<?php

	add_action( 'wp_footer', 'hiweb\\errors\\display::echo_footerErrorsHtml' );
	add_action( 'admin_footer', 'hiweb\\errors\\display::echo_footerErrorsHtml' );