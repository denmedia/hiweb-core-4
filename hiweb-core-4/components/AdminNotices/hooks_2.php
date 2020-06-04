<?php

	add_action( 'admin_menu', function(){
		?>
		<style id="hiweb-components-adminnotices-inline-styles"><?= file_get_contents( __DIR__ . '/inline-styles.css' ); ?></style>
		<?php
	}, 1 );
	add_action( 'admin_notices', function(){
		?>
		<div id="hiweb-components-adminnotices-wrap"></div>
		<?php
	} );