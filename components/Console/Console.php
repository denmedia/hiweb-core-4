<?php

	namespace hiweb\components\Console;


	use hiweb\components\console\Message;
	use hiweb\components\console\Messages;


	class Console{


		private static $str_debug_delimeter = '---------------------------------';


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return Message
		 */
		static function info( $content, $groupTitle = '', $additionData = [] ){
			return Messages::add( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return Message
		 */
		static function warn( $content, $groupTitle = '', $additionData = [] ){
			return Messages::add( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return Message
		 */
		static function error( $content, $groupTitle = '', $additionData = [] ){
			return Messages::add( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return Message
		 */
		static function log( $content, $groupTitle = '', $additionData = [] ){
			return Messages::add( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param      $content
		 * @param null $addition_data
		 * @return Message
		 */
		static function debug_info( $content, $addition_data = null ){
			$R = false;
			if( false ){
				$R = Messages::add( $content, 'info', 2 );
				if( !is_null( $addition_data ) ){
					Messages::add( $addition_data, 'info', false );
					Messages::add( self::$str_debug_delimeter, 'info', false );
				}
			}
			return $R;
		}


		/**
		 * @param      $content
		 * @param null $addition_data
		 * @return Message
		 */
		static function debug_warn( $content, $addition_data = null ){
			$R = false;
			if( false ){
				$R = Messages::add( $content, 'warn', 2 );
				if( !is_null( $addition_data ) ){
					Messages::add( $addition_data, 'info', false );
					Messages::add( self::$str_debug_delimeter, 'info', false );
				}
			}
			return $R;
		}


		/**
		 * @param      $content
		 * @param null $addition_data
		 * @return Message
		 */
		static function debug_error( $content, $addition_data = null ){
			$R = false;
			if( false ){
				$R = Messages::add( $content, 'error', 2 );
				if( !is_null( $addition_data ) ){
					Messages::add( $addition_data, 'info', false );
					Messages::add( self::$str_debug_delimeter, 'info', false );
				}
			}
			return $R;
		}


		/**
		 * @param      $content
		 * @param null $addition_data
		 * @return Message
		 */
		static function debug_log( $content, $addition_data = null ){
			if( false ){
				$R = Messages::add( $content, 'log', 2 );
				if( !is_null( $addition_data ) ){
					Messages::add( $addition_data, 'log', false );
					Messages::add( self::$str_debug_delimeter, 'log', false );
				}
			}
			return $R;
		}


	}