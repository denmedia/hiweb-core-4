<?php
	
	namespace theme\forms;
	
	
	use hiweb\components\Client\Client;
	use hiweb\components\Date;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\Paths\PathsFactory;
	use theme\forms;
	
	
	class form_submit{
		
		/**
		 * @var form
		 */
		private $form;
		private $submit_data;
		private $content_array = [ '{data-list}' => '' ];
		private $required_errors = [];
		private $client_emails = [];
		
		
		public function __construct( form $form, $submit_data ){
			$this->form = $form;
			$this->submit_data = ArrayObject::get_instance( $submit_data );
			$this->init();
		}
		
		
		private function init(){
			$inputs = $this->form->get_inputs_options();
			foreach( $inputs as $input ){
				$input = ArrayObject::get_instance( $input );
				if( $input->_( 'name' ) == '' ) continue;
				$name = $input->_( 'name' );
				$input_object = $this->form->get_input_object( $name );
				$submit_input_data = $this->submit_data->_( $name );
				$value = $input_object->get_email_value( $submit_input_data );
				$this->content_array[ '{' . $name . '}' ] = $value;
				if( $submit_input_data != '' && $input_object->is_email_submit_enable() ) $this->content_array['{data-list}'] .= $input_object->get_email_html( $submit_input_data ) . "<br>";
				if( $input_object->is_required() && !$input_object->is_required_validate( $submit_input_data ) ){
					$require_message = $input->_( 'require-message' );
					$this->required_errors[ $name ] = $require_message == '' ? __( 'Invalid form field', 'hiweb-core-4' ) : $require_message;
				}
				if( $input_object->is_type_email() && $input_object->is_required_validate( $submit_input_data ) ){
					$this->client_emails[ $name ] = $submit_input_data;
				}
			}
		}
		
		
		/**
		 * @return form
		 */
		public function get_form(){
			return $this->form;
		}
		
		
		/**
		 * @return ArrayObject
		 */
		public function get_submit_data(){
			return $this->submit_data;
		}
		
		
		/**
		 * @return string
		 */
		public function get_mail_content(){
			$R = '';
			
			return $R;
		}
		
		
		/**
		 * @return array
		 */
		public function get_required_errors(){
			return $this->required_errors;
		}
		
		
		/**
		 * @return bool
		 */
		public function is_required_no_errors(){
			return count( $this->required_errors ) == 0;
		}
		
		
		public function insert_message( $to = '', $subject = '', $content = '' ){
			if( !$this->form->is_exists() ) return;
			$new_message_id = wp_insert_post( [
				'post_type' => forms::$post_type_messages_name,
				'post_status' => 'publish',
				'post_content' => $content,
				'post_title' => Date::format() . ' - ' . $this->form->get_wp_post()->post_title
			] );
			if( is_int( $new_message_id ) ){
				///message data insert
				update_post_meta( $new_message_id, 'form-id', $this->form->get_id() );
				update_post_meta( $new_message_id, 'form-data-post', $_POST );
				update_post_meta( $new_message_id, 'form-data-get', $_GET );
				update_post_meta( $new_message_id, 'form-recipient', $to );
				update_post_meta( $new_message_id, 'form-subject', $subject );
				update_post_meta( $new_message_id, 'client-ip', client::get_instance()->get_ip() );
				update_post_meta( $new_message_id, 'client-user-agent', $_SERVER['HTTP_USER_AGENT'] );
				update_post_meta( $new_message_id, 'client-browser-name', client::get_instance()->get_browser() );
				update_post_meta( $new_message_id, 'client-os', client::get_instance()->get_os() );
				update_post_meta( $new_message_id, 'client-os2', client::get_instance()->get_os2() );
				update_post_meta( $new_message_id, 'client-id', client::get_instance()->get_id_OsIp() );
				//utm points
				if( !forms::get_utm_points_options()->is_empty() ){
					$utm_points = [];
					foreach( forms::get_utm_points_options()->get() as $point ){
						if( isset( $_SESSION[ forms::$utm_point_session_key ][ $point ] ) ){
							if( trim( $_SESSION[ forms::$utm_point_session_key ][ $point ] ) != '' ){
								$utm_points[ $point ] = $_SESSION[ forms::$utm_point_session_key ][ $point ];
							}
							unset( $_SESSION[ forms::$utm_point_session_key ][ $point ] );
						}
					}
					update_post_meta( $new_message_id, 'utm-points', $utm_points );
				}
			}
		}
		
		
		/**
		 * @param string $to
		 * @param string $subject
		 * @param string $content
		 * @return bool
		 */
		public function send_mail( $to = '', $subject = '', $content = '' ){
			if( !is_string( $to ) || trim( $to ) == '' ){
				$to = get_bloginfo( 'admin_email' );
				if( !filter_var( $to, FILTER_VALIDATE_EMAIL ) ){
					$to = get_option( 'admin_email' );
				}
			}
			$reply_to = 'noreply@{domain}';
			if( get_field( 'reply_email', $this->form->get_wp_post() ) != '' ){
				$reply_to = get_field( 'reply_email', forms::$options_name );
			}
			if( get_field( 'reply_email', $this->form->get_wp_post() ) != '' ){
				$reply_to = get_field( 'reply_email', $this->form->get_wp_post() );
			}
			$reply_to = str_replace( '{domain}', PathsFactory::get_url()->domain(), $reply_to );
			$headers = [ 'From: ' . get_bloginfo( 'name' ) . ' <' . $reply_to . '>' ];
			$headers[] = 'Reply-To: ' . $reply_to;
			$headers[] = 'Precedence: bulk';
			$headers[] = 'List-Unsubscribe: ' . PathsFactory::root()->get_url( false ) . '?unsubscribe';
			add_filter( 'wp_mail_content_type', function(){ return "text/html"; } );
			///
			return wp_mail( $to, html_entity_decode( $subject ), $content, $headers );
		}
		
		
		/**
		 * @return array
		 */
		public function get_target_emails_admin(){
			$emails = [];
			$emails_str = trim( get_field( 'email', $this->form->get_wp_post() ), ',' );
			if( $emails_str == '' ) $emails_str = trim( get_field( 'email', forms::$options_name ), ',' );
			if( $emails_str == '' ){
				$emails = [ get_bloginfo( 'admin_email' ) ];
			}
			elseif( strpos( $emails_str, ' ' ) ){
				$emails = explode( ' ', $emails_str );
			}
			elseif( strpos( $emails_str, ',' ) ){
				$emails = explode( ',', $emails_str );
			}
			elseif( trim( $emails_str ) != '' ){
				$emails = [ trim( $emails_str ) ];
			}
			$R = [];
			foreach( $emails as $test_email ){
				$test_email = trim( $test_email );
				if( $test_email == '' ) continue;
				if( filter_var( $test_email, FILTER_VALIDATE_EMAIL ) ) $R[] = $test_email;
			}
			return $R;
		}
		
		
		/**
		 * @return array|string[]
		 */
		public function get_target_emails_client(){
			return $this->client_emails;
		}
		
		
		/**
		 * @return string
		 */
		public function get_email_theme_admin(){
			$theme = get_field('theme-email-admin', $this->form->get_wp_post(), '');
			if($theme == '') {
				$theme = get_field('theme-email-admin', forms::$options_name);
			}
			return strtr( $theme, $this->form->get_strtr_templates( $this->content_array ) );
		}
		
		
		/**
		 * @return string
		 */
		public function get_email_content_admin(){
			$email_content_admin = get_field( 'content-email-admin', $this->form->get_wp_post() );
			if(trim($email_content_admin) == '') {
				$email_content_admin = get_field( 'content-email-admin', forms::$options_name );
			}
			return apply_filters( 'the_content', strtr( $email_content_admin, $this->form->get_strtr_templates( $this->content_array ) ) );
		}
		
		
		/**
		 * @return string
		 */
		public function get_email_theme_client(){
			$theme = get_field('theme-email-client', $this->form->get_wp_post(), '');
			if($theme == '') {
				$theme = get_field('theme-email-client', forms::$options_name);
			}
			return strtr( $theme, $this->form->get_strtr_templates( $this->content_array ) );
		}
		
		
		/**
		 * @return string
		 */
		public function get_email_content_client(){
			return apply_filters( 'the_content', strtr( get_field( 'content-email-client', $this->form->get_wp_post() ), $this->form->get_strtr_templates( $this->content_array ) ) );
		}
		
	}