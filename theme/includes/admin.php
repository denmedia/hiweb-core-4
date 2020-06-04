<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 01:34
	 */

	namespace theme\includes;




	use hiweb\components\Context;
	use hiweb\components\Includes\IncludesFactory;
	use hiweb\components\Includes\Js;
	
	
	class admin extends includes{
		
		/**
		 * @param       $filePathOrUrl
		 * @param bool  $in_footer
		 * @param array $deeps
		 * @return bool|Js
		 */
		static function css( $filePathOrUrl, $in_footer = false, $deeps = [] ){
			return IncludesFactory::css($filePathOrUrl)->to_footer($in_footer)->deeps($deeps)->on_admin(true);
		}
		
		
		/**
		 * @param       $jsPathOrURL
		 * @param array $deeps
		 * @param bool  $inFooter
		 * @return bool|Js
		 */
		static function js( $jsPathOrURL, $deeps = [], $inFooter = true ){
			return IncludesFactory::js($jsPathOrURL)->deeps($deeps)->to_footer($inFooter)->on_admin(true);
			//return parent::js( $jsPathOrURL, $deeps, $inFooter );
		}


		static function jquery( $include_migrate_js = false ){
			wp_enqueue_script( 'jquery-core' );
		}


	}