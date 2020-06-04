<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 02:10
	 */
	
	namespace theme\html_layout\tags;
	
	
	use hiweb\core\ArrayObject\ArrayObject;
	
	
	class body{
		
		/** @var ArrayObject */
		static private $classes = [];
		/** @var ArrayObject */
		static private $tags = [];
		/** @var bool */
		static $use_wp_class = true;
		static $use_wp_footer = true;
		
		
		/**
		 * Print <body ...>
		 */
		static function the_before(){
			do_action( '\theme\html_layout\body::the_before-before' );
			get_template_part( HIWEB_THEME_PARTS . '/html_layout/body-before' );
			do_action( '\theme\html_layout\body::the_before-after' );
		}
		
		
		/**
		 * Print </body>
		 */
		static function the_after(){
			do_action( '\theme\html_layout\body::the_after-before' );
			get_template_part( HIWEB_THEME_PARTS . '/html_layout/body-after' );
			do_action( '\theme\html_layout\body::the_after-after' );
		}
		
		
		/**
		 * @return array|ArrayObject
		 */
		static function get_class_array(){
			if( !self::$classes instanceof ArrayObject ){
				self::$classes = new ArrayObject();
			}
			return self::$classes;
		}
		
		
		/**
		 * @return ArrayObject
		 */
		static function get_tags_array(){
			if( !self::$tags instanceof ArrayObject ){
				self::$tags = new ArrayObject();
			}
			return self::$tags;
		}
		
		
		/**
		 *Print body classe tag
		 */
		static function the_classes(){
			if( self::$use_wp_class ){
				body_class( self::get_class_array()->get() );
			}
			else{
				echo implode( ' ', self::get_class_array()->get() );
			}
		}
		
		
		/**
		 * Print body tag params
		 */
		static function the_tags(){
			if( self::$tags instanceof ArrayObject ){
				echo self::$tags->get_param_html_tags();
			}
		}
		
	}