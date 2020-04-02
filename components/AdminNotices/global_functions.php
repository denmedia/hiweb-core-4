<?php

	if( !function_exists( 'init_adminNotices' ) ){

		function init_adminNotices(){
			\hiweb\components\AdminNotices\AdminNotices::init();
		}
	}