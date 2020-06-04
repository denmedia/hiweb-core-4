<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 9:19
	 */

	namespace theme;


	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\Paths\PathsFactory;
	use theme\forms\form;
	use theme\includes\frontend;


	class forms{

		static $post_type_name = 'hiweb-forms';
		static $post_type_messages_name = 'hiweb-forms-messages';
		static protected $post_type_object;
		static $options_name = 'hiweb-forms';
		static protected $options_object;
		static $template_name = 'default';
		/** @var \WP_Post current form wp post */
		static protected $the_wp_post;
		/** @var form[] */
		static protected $forms = [];
		static $input_classes;
		/** @var bool */
		static $mailchimp_enable = false;
		/** @var mailchimp */
		static protected $mailchimp;
		static $enqueue_frontend_scripts = true;
		static private $utm_points;
		static $utm_point_session_key = 'hiweb-forms-utm-points';
		static private $utm_point_string_limit = 128;


		static function init(){
			require_once __DIR__ . '/post-type.php';
			require_once __DIR__ . '/post-type-messages.php';
			require_once __DIR__ . '/options.php';
			require_once __DIR__ . '/rest.php';
			require_once __DIR__ . '/shortcode.php';
			require_once __DIR__ . '/widget.php';
			if( self::$enqueue_frontend_scripts ){
				frontend::css( __DIR__ . '/assets/forms.css' );
				frontend::js( __DIR__ . '/assets/forms.min.js', [ frontend::jquery(), frontend::jquery_mask(), frontend::jquery_form() ] );
				frontend::fancybox();
				if( recaptcha::is_enable() && strlen( recaptcha::get_recaptcha_key() ) > 5 ){
					frontend::js( 'https://www.google.com/recaptcha/api.js?render=' . recaptcha::get_recaptcha_key(), [], false );
				}
			}
			///
			if( !self::get_utm_points_options()->is_empty() ){
				if( !self::is_session_started() ) session_start();
				foreach( self::get_utm_points_options()->get() as $point ){
					if( isset( $_GET[ $point ] ) ){
						$_SESSION[ self::$utm_point_session_key ][ $point ] = substr( $_GET[ $point ], 0, self::$utm_point_string_limit );
					}
				}
			}
		}


		/**
		 * @return bool
		 */
		static private function is_session_started(){
			if( php_sapi_name() !== 'cli' ){
				if( version_compare( phpversion(), '5.4.0', '>=' ) ){
					return session_status() === PHP_SESSION_ACTIVE ? true : false;
				} else {
					return session_id() === '' ? false : true;
				}
			}
			return false;
		}


		/**
		 * @return ArrayObject
		 */
		static function get_utm_points_options(){
			if( self::$utm_points instanceof ArrayObject ) return self::$utm_points;
			///
			$R = get_array();
			$test_utms = get_field( 'utm-points', self::$post_type_name );
			if( $test_utms != '' ){
				foreach( explode( "\n", $test_utms ) as $point ){
					$point = trim( $point );
					if( $point != '' ) $R->push( $point );
				}
			}
			self::$utm_points = $R;
			return $R;
		}


		/**
		 * @return mailchimp
		 */
		static function mailchimp(){
			if( !self::$mailchimp instanceof mailchimp ){
				self::$mailchimp = new mailchimp( get_field( 'api-key', mailchimp::$options_name_mailchimp ) );
			}
			return self::$mailchimp;
		}


		static function get_strtr_templates( $additions = [], $return_descriptions = false ){
			$data = [
				'#{home-url}' => [ get_home_url(), 'URL адрес домашней страницы' ],
				'{site-name}' => [ get_bloginfo( 'name' ), 'Название сайта' ],
				'{form-title}' => [ 'Название формы', 'Заголовок формы' ]
			];
			$R = [];
			foreach( $data as $key => $raw ){
				$R[ $key ] = $return_descriptions ? $raw[1] : $raw[0];
			}
			if( is_array( $additions ) ) foreach( $additions as $key => $raw ){
				$R[ $key ] = is_array( $raw ) ? ( $return_descriptions ? $raw[1] : $raw[0] ) : $raw;
			}
			return $R;
		}


		/**
		 * @param $form_post_id
		 * @return form
		 */
		public static function get( $form_post_id ){
			if( !array_key_exists( $form_post_id, self::$forms ) ){
				self::$forms[ $form_post_id ] = new form( $form_post_id );
			}
			return self::$forms[ $form_post_id ];
		}


		/**
		 * @return array
		 */
		static function get_input_classes(){
			if( !is_array( self::$input_classes ) ){
				self::$input_classes = [];
				foreach( PathsFactory::get_file( __DIR__ . '/inputs' )->get_sub_files( [ 'php' ] ) as $path => $class_file ){
					self::$input_classes[ $class_file->filename() ] = ( 'theme\\forms\\inputs\\' . $class_file->filename() );
				}
			}
			return self::$input_classes;
		}


		/**
		 * @param $form_postOrId
		 * @return array|null|\WP_Post
		 */
		static function setup_postdata( $form_postOrId ){
			self::$the_wp_post = get_post( $form_postOrId );
			return self::$the_wp_post;
		}


		/**
		 * @return int|null
		 */
		static function get_the_ID(){
			if( self::$the_wp_post instanceof \WP_Post ){
				return self::$the_wp_post->ID;
			} else return null;
		}


		/**
		 * @return form
		 */
		static function get_the_form(){
			return self::get( self::get_the_ID() );
		}

	}