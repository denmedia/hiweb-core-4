<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 20:12
	 */

	namespace theme\html_layout\tags;


	/**
	 * Class head отвечает за вывод главного тега HEAD в HTML
	 * @package hiweb_theme
	 */
	class head{

		/**
		 * @var string
		 * properties:
		 * width
		 * height
		 * initial-scale
		 * minimum-scale
		 * maximum-scale
		 * user-scalable
		 * values:
		 * 0...9
		 * device-width
		 * device-height
		 */
		static $meta_viewport = 'width=device-width, initial-scale=1';
		/** @var bool Use */
		static $use_wp_head = true;

		static $use_emoji = false;

		static $show_meta_generator = false;

		static $show_link_wlwmanifest = false;

		static $show_link_rel_EditURI = false;

		static $show_RSS_links = false;

		static $show_restApi_link = false;

		static $use_wp_embed = false;

		static $code = [];

		static $html_tags = [];

		static $html_addition = [];


		static $use_wp_title = true;


		/**
		 * Print head tag and body prefix
		 */
		static function the(){
			do_action( '\theme\html_layout\head::the-before' );
			get_template_part( HIWEB_THEME_PARTS . '/html_layout/head' );
			do_action( '\theme\html_layout\head::the-after' );
		}


		/**
		 * Print meta viewport
		 */
		static function the_meta_viewport(){
			do_action( '\theme\html_layout\head::the_meta_viewport-before' );
			get_template_part( HIWEB_THEME_PARTS . '/html_layout/head-meta_viewport' );
			do_action( '\theme\html_layout\head::the_meta_viewport-after' );
		}


		/**
		 * @param $code
		 * @return array
		 */
		static function add_code( $code ){
			self::$code[] = $code;
			return self::$code;
		}


		/**
		 * Add to HTML addition inside tags
		 * @param      $name
		 * @param null $value
		 */
		static function add_html_tag( $name, $value = null ){
			self::$html_tags[ $name ] = $value;
		}


		/**
		 * @param bool $return_array
		 * @return array|string
		 */
		static function get_html_tags( $return_array = false ){
			$R = [];
			if( is_array( self::$html_tags ) )
				foreach( self::$html_tags as $name => $value ){
					$R[] = $name . '="' . htmlentities( $value ) . '"';
				}
			return $return_array ? $R : join( ' ', $R );
		}


		/**
		 * Add some html or scripts to head
		 * @param $html
		 */
		static function add_html_addition( $html ){
			self::$html_addition[] = $html;
		}


		/**
		 * @param bool $return_array
		 * @return array|string
		 */
		static function get_htmlAddition( $return_array = false ){
			$R = apply_filters( 'hiweb-theme-head-html-addition-array', self::$html_addition );
			$R = $return_array ? apply_filters( 'hiweb-theme-head-html-addition-string', $R ) : join( "\n", $R );
			return $R;
		}


	}