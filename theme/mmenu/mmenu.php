<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 9:42
	 */

	namespace theme;


	use theme\includes\frontend;
	use theme\mmenu\extensions;


	class mmenu{

		/** @var mmenu[] */
		static $mmenus = [];
		static $init = false;

		private $nav_location;
		private $extensions;
		private $menu_title = 'Меню';


		static function init(){
			if( !self::is_init() ){
				self::$init = true;
				$mmenu_handle = frontend::jquery_mmenu();
				frontend::css( __DIR__ . '/mmenu.css' );
				frontend::js( __DIR__ . '/mmenu.min.js', $mmenu_handle );
				///
				add_action( '\theme\html_layout\body::the_before-after', function(){
					get_template_part( HIWEB_THEME_PARTS . '/mmenu/before' );
				}, 1 );
				add_action( '\theme\html_layout\body::the_after-before', function(){
					get_template_part( HIWEB_THEME_PARTS . '/mmenu/after' );
				}, 9999999 );
				///
				add_action( '\theme\html_layout\body::the_after-before', function(){
					foreach( self::get_all() as $mmenu ){
						$nav_menu = nav_menu::get( $mmenu->get_nav_location() );
						$nav_menu->use_stellarnav = false;
						$nav_menu->add_class( 'hiweb-mmenu-nav' );
						$nav_menu->add_tag( 'data-title', htmlentities( $mmenu->get_title() ) );
						$nav_menu->add_tag( 'data-extensions', json_encode( $mmenu->extensions()->get_data_array() ) );
						$nav_menu->id = $mmenu->get_nav_id();
						//$nav_menu->add_class('');
						$nav_menu->the();
					}
				} );
			}
		}


		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}


		/**
		 * @param string $nav_location
		 * @return mmenu
		 */
		static function add( $nav_location = 'mmenu' ){
			self::init();
			if( !array_key_exists( $nav_location, self::$mmenus ) ){
				self::$mmenus[ $nav_location ] = new mmenu( $nav_location );
			}

			return self::$mmenus[ $nav_location ];
		}


		static function get( $nav_location ){
			if( array_key_exists( $nav_location, self::$mmenus ) ){
				return self::$mmenus[ $nav_location ];
			}
			return new mmenu( '' );
		}


		/**
		 * @return mmenu[]
		 */
		static function get_all(){
			return self::$mmenus;
		}


		/**
		 * mmenu constructor.
		 * @param $nav_location
		 */
		public function __construct( $nav_location ){
			$this->nav_location = $nav_location;
		}


		/**
		 * @param string $title
		 * @return $this
		 */
		public function set_title( $title = 'Меню' ){
			$this->menu_title = $title;
			return $this;
		}


		/**
		 * @return string
		 */
		public function get_id(){
			return 'hiweb-mmenu-' . $this->nav_location;
		}


		/**
		 * @return string
		 */
		public function get_nav_id(){
			return 'hiweb-mmenu-nav-' . $this->get_nav_location();
		}


		/**
		 * @return string
		 */
		public function get_nav_location(){
			return $this->nav_location;
		}


		/**
		 * @return string
		 */
		public function get_title(){
			return $this->menu_title;
		}


		/**
		 * @return extensions
		 */
		public function extensions(){
			if( !$this->extensions instanceof extensions ){
				$this->extensions = new extensions( $this );
			}
			return $this->extensions;
		}


		//		public function get_html_tag_options(){
		//
		//		}

	}