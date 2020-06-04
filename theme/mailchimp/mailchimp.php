<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 18/11/2018
	 * Time: 10:53
	 */

	namespace theme;


	use hiweb\core\hidden_methods;


	/**
	 * Class mailchimp
	 * @package theme\forms
	 */
	class mailchimp{

		static $options_name_mailchimp = 'hiweb-forms-mailchimp';
		static $options_object_mailchimp;

		protected $api_key = '';
		/** @var \DrewM\MailChimp\MailChimp */
		protected $mail_chimp_api;
		/**
		 * @var
		 */
		protected $lists;
		protected $is_connected;


		use hidden_methods;


		static function init(){
			\theme\forms::init();
			require_once __DIR__ . '/options.php';
			add_action('');
		}


		/**
		 * mailchimp constructor.
		 * @param $api_key
		 */
		public function __construct( $api_key ){
			require_once( HIWEB_THEME_VENDORS_DIR . '/mailchimp-api/MailChimp.php' );
			$this->api_key = trim( $api_key );
			try{
				$this->mail_chimp_api = new \DrewM\MailChimp\MailChimp( $this->api_key );
			} catch( \Exception $e ){

			}
		}


		/**
		 * @return \DrewM\MailChimp\MailChimp
		 */
		public function get_api(){
			return $this->mail_chimp_api;
		}


		/**
		 * @return array
		 */
		public function get_lists(){
			if( !$this->is_api_key_exists() )
				return [];
			if( !is_array( $this->lists ) ){
				$this->lists = [];
				$lists_raw = $this->mail_chimp_api->get( 'lists' );
				if( is_array( $lists_raw ) && is_array( $lists_raw['lists'] ) ){
					foreach( $lists_raw['lists'] as $list ){
						$this->lists[ $list['id'] ] = $list;
					}
					$this->is_connected = true;
				}
				if( !is_array( $lists_raw ) ){
					$this->is_connected = false;
				}
			}
			return $this->lists;
		}


		/**
		 * @return array
		 */
		public function get_list_ids(){
			return array_keys( $this->get_lists() );
		}


		public function get_list_ids_names(){
			$R = [];
			foreach( self::get_lists() as $id => $list_data ){
				$R[ $id ] = $list_data['name'];
			}
			return $R;
		}


		/**
		 * @return bool
		 */
		public function is_api_key_exists(){
			return trim( get_field( 'api-key', self::$options_name_mailchimp ) ) != '';
		}


		/**
		 * @return bool
		 */
		public function is_connected(){
			if( !$this->is_api_key_exists() )
				return false;
			$this->get_lists();
			return $this->is_connected;
		}


		/**
		 * @param $list_id
		 * @return bool
		 */
		public function is_list_exists( $list_id ){
			if( !$this->is_connected() )
				return false;
			return array_key_exists( $list_id, $this->get_lists() );
		}

	}