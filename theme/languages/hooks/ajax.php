<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-19
	 * Time: 16:25
	 */

	add_action( 'wp_ajax_hiweb_theme_languages_multisite_migrate_post', function(){
		$B = \theme\languages\multisites::do_mirgate_current_post_to_site( $_POST['post_id'], $_POST['site_id'] );
		if( intval($B) > 0 || (is_array($B) && count($B) > 0) ){
			wp_send_json_success( $B );
		} else {
			wp_send_json_error( 'Ошибка импорта, номер ошибки [' . $B . ']' );
		}
		die;
	} );