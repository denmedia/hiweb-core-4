<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 01:31
	 */


	namespace theme\includes;



	use hiweb\components\Includes\Js;
	
	
	class frontend extends includes{

		static $use_wp_block_library = false;
		static $use_wp_jquery_core = false;
		
		
		/**
		 * @param       $filePathOrUrl
		 * @param bool  $in_footer
		 * @param array $deeps
		 * @return Js
		 */
		static function css( $filePathOrUrl, $in_footer = false, $deeps = [] ){
			return parent::css( $filePathOrUrl, $in_footer, $deeps )->on_frontend(true);
		}


		/**
		 * @param       $jsPathOrURL
		 * @param array $deeps
		 * @param bool  $inFooter
		 */
		static function js( $jsPathOrURL, $deeps = [], $inFooter = true ){
			return parent::js( $jsPathOrURL, $deeps, $inFooter )->on_frontend(true);
		}


		static function wp_block_library(){
			self::$use_wp_block_library = true;
		}

	}