<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 17:58
	 */
	
	if( !function_exists( 'send_mail' ) ){
		
		/**
		 * Send html content mail
		 * @param string $to - оставьте поле пустым, чтобы писбмо было отправлено супер-администратору сайта
		 * @param        $subject
		 * @param        $content
		 * @version 1.1
		 */
		function send_mail( $to = '', $subject = '', $content = '', $from = 'noreply' ){ //TODO: не работает корректно
			if( !is_string( $to ) || trim( $to ) == '' ){
				$to = get_bloginfo( 'admin_email' );
				if( !filter_var( $to, FILTER_VALIDATE_EMAIL ) ){
					$to = get_option( 'admin_email' );
				}
			}
			$headers = [ 'From: ' . get_bloginfo( 'name' ) . ' <' . $from . '@' . \hiweb\core\Paths\PathsFactory::root()->url()->domain() . '>' ];
			$headers[] = 'Reply-To: noreply@' . \hiweb\core\Paths\PathsFactory::root()->url()->domain() . '';
			$headers[] = 'Precedence: bulk';
			$headers[] = 'List-Unsubscribe: ' . \hiweb\core\Paths\PathsFactory::root()->get_url( false );
			add_filter( 'wp_mail_content_type', function(){ return "text/html"; } );
			wp_mail( $to, html_entity_decode( $subject ), $content, $headers );
		}
	}