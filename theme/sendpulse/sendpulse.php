<?php

	namespace theme;


	use Exception;
	use hiweb\admin\pages\page;
	use Sendpulse\RestApi\ApiClient;
	use Sendpulse\RestApi\Storage\FileStorage;


	class sendpulse{

		static $options_name = 'hiweb-forms-sendpulse';
		/** @var page */
		static $options_object;
		static private $instance;
		static private $init = false;


		static function init(){
			forms::init();
			if( !self::$init ){
				self::$init = true;
				require_once __DIR__ . '/options.php';
			}
		}


		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}


		/**
		 * @return bool
		 */
		static function is_id_key_exists(){
			return get_field( 'api-id', self::$options_name ) != '';
		}


		/**
		 * @return bool
		 */
		static function is_secret_key_exists(){
			return get_field( 'api-secret', self::$options_name ) != '';
		}


		/**
		 * @return bool
		 */
		static function is_keys_exists(){
			return self::is_id_key_exists() && self::is_secret_key_exists();
		}


		/**
		 * @return sendpulse
		 */
		static function get_instance(){
			if( !self::$instance instanceof sendpulse ){
				self::$instance = new sendpulse();
			}
			return self::$instance;
		}


		///////////

		private $id;
		private $secret;
		private $sendpulse_api;
		private $lists;


		public function __construct(){
			$this->id = trim( get_field( 'api-id', self::$options_name ) );
			$this->secret = trim( get_field( 'api-secret', self::$options_name ) );
		}


		/**
		 * @return bool|ApiClient
		 */
		public function get_api(){
			if( is_null( $this->sendpulse_api ) ){
				$this->sendpulse_api = false;
				if( self::is_keys_exists() ){
					require_once HIWEB_THEME_VENDORS_DIR . '/sendpulse-rest-api/ApiInterface.php';
					require_once HIWEB_THEME_VENDORS_DIR . '/sendpulse-rest-api/Storage/TokenStorageInterface.php';
					get_path( HIWEB_THEME_VENDORS_DIR . '/sendpulse-rest-api' )->include_files( 'php' );
					try{
						$this->sendpulse_api = new ApiClient( $this->id, $this->secret, new FileStorage() );
					} catch( Exception $e ){

					}
				}
			}
			return $this->sendpulse_api;
		}


		/**
		 * @return bool
		 */
		public function is_api_exists(){
			return $this->get_api() instanceof ApiClient;
		}


		/**
		 * @return array
		 */
		public function get_lists(){
			if( !$this->is_api_exists() ) return [];
			if( !is_array( $this->lists ) ){
				$this->lists = [];
				$lists = get_array( $this->get_api()->listAddressBooks() );
				while( $lists->have_rows() ){
					$lists->the_row();
					$this->lists[ $lists->get_sub_field( 'id' ) ] = $lists->get_sub_field( 'name' ) . ' (' . $lists->get_sub_field( 'active_email_qty' ) . '/' . $lists->get_sub_field( 'all_email_qty' ) . ' mails, ' . $lists->get_sub_field( 'status_explain' ) . ')';
				}
			}
			return $this->lists;
		}


		/**
		 * @param $list_id
		 * @return bool
		 */
		public function is_list_exists($list_id){
			return array_key_exists( $list_id, $this->get_lists() );
		}

	}