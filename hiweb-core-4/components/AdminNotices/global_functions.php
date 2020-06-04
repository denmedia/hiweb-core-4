<?php

	if( !function_exists( 'init_adminNotices' ) ){

		function init_adminNotices(){
			\hiweb\components\AdminNotices\AdminNotices::init();
		}
	}
	
	
	if(!function_exists('add_admin_notice')) {
		
		function add_admin_notice($notice){
			//TODO!
		}
		
	}