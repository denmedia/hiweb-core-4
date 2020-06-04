<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 9:22
	 */

	namespace theme;


	use theme\includes\frontend;


	class hamburgers{

		/** @var hamburgers[] */
		static private $burgers = [];
		static private $init = false;

		private $link = '#mobile-menu';


		static function init(){
			if( !self::is_init() ){
				self::$init = true;
				frontend::css( HIWEB_THEME_VENDORS_DIR . '/hamburgers/hamburgers.min.css' );
				frontend::js( HIWEB_THEME_VENDORS_DIR . '/hamburgers/hamburders.min.js' );
			}
		}


		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}


		/**
		 * @param null|string $link - mmenu id or href burger content. If empty = auto find mmenu id
		 * @return hamburgers
		 */
		static function get( $link = null ){
			if( !is_string( $link ) ){
				if( mmenu::is_init() ){
					if( is_array( mmenu::get_all() ) && count( mmenu::get_all() ) > 0 ){
						$mmenus = mmenu::get_all();
						$link = '#' . reset($mmenus)->get_nav_id();
					}
				}
			}
			if( !array_key_exists( $link, self::$burgers ) ){
				self::$burgers[ $link ] = new hamburgers( $link );
			}
			return self::$burgers[ $link ];
		}


		/**
		 * @return hamburgers[]
		 */
		static function get_all(){
			return self::$burgers;
		}


		public function __construct( $button_link = '#mobile-menu' ){
			self::init();
			$this->link = $button_link;
		}


		public function the(){
			global $hiweb_theme_module_hamburgers_link;
			$hiweb_theme_module_hamburgers_link = $this->link;
			get_template_part( HIWEB_THEME_PARTS . '/modules/hamburgers/button' );
		}


		/**
		 * @return string
		 */
		public function html(){
			ob_start();
			$this->the();
			return ob_get_clean();
		}


		/**
		 * @return string
		 */
		public function __toString(){
			return $this->html();
		}

	}