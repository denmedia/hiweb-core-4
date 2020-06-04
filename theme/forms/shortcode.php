<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 16:18
	 */

	use theme\forms;


	add_shortcode( 'hiweb-theme-widget-form', function( $atts ){
		return forms::get( get_array( $atts )->value_by_key( 'id' ) )->get_html();
	} );

	add_shortcode( 'hiweb-theme-widget-form-button', function( $atts ){
		return forms::get( get_array( $atts )->value_by_key( 'id' ) )->get_fancybox_button( get_array( $atts )->value_by_key( 'html' ) );
	} );