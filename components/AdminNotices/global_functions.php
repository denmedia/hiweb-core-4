<?php
	
	use hiweb\components\AdminNotices\AdminNotice;
	
	
	if( !function_exists( 'init_adminNotices' ) ){
		
		function init_adminNotices(){
			\hiweb\components\AdminNotices\AdminNotices_Factory::init();
		}
	}
	
	if( !function_exists( 'add_admin_notice' ) ){
		/**
		 * @param string $notice_content
		 * @param string $notice_title
		 * @return AdminNotice
		 */
		function add_admin_notice( $notice_content, $notice_title = '' ){
			$AdminNotice = \hiweb\components\AdminNotices\AdminNotices_Factory::add_wp_notice( $notice_content, $notice_title );
			$AdminNotice->options()->info();
			return $AdminNotice;
		}
	}