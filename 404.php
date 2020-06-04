<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 23/10/2018
	 * Time: 11:30
	 */

	theme\error_404::init();

	get_header( 'error-404' );

	get_template_part( HIWEB_THEME_PARTS . '/404-content' );

	get_footer( 'error-404' );