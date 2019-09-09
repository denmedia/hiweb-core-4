<?php

	namespace hiweb\components\console;


	class Console{


		private static $str_debug_delimeter = '---------------------------------';


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return Message
		 */
		static function info( $content, $groupTitle = '', $additionData = [] ){
			return Messages::make( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return Message
		 */
		static function warn( $content, $groupTitle = '', $additionData = [] ){
			return Messages::make( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return Message
		 */
		static function error( $content, $groupTitle = '', $additionData = [] ){
			return Messages::make( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param        $content
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @return Message
		 */
		static function log( $content, $groupTitle = '', $additionData = [] ){
			return Messages::make( $content, __FUNCTION__, $groupTitle, $additionData );
		}


		/**
		 * @param      $content
		 * @param null $addition_data
		 * @return Message
		 */
		static function debug_info( $content, $addition_data = null ){
			$R = false;
			if( false ){
				$R = Messages::make( $content, 'info', 2 );
				if( !is_null( $addition_data ) ){
					Messages::make( $addition_data, 'info', false );
					Messages::make( self::$str_debug_delimeter, 'info', false );
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
				$R = Messages::make( $content, 'warn', 2 );
				if( !is_null( $addition_data ) ){
					Messages::make( $addition_data, 'info', false );
					Messages::make( self::$str_debug_delimeter, 'info', false );
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
				$R = Messages::make( $content, 'error', 2 );
				if( !is_null( $addition_data ) ){
					Messages::make( $addition_data, 'info', false );
					Messages::make( self::$str_debug_delimeter, 'info', false );
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
				$R = Messages::make( $content, 'log', 2 );
				if( !is_null( $addition_data ) ){
					Messages::make( $addition_data, 'log', false );
					Messages::make( self::$str_debug_delimeter, 'log', false );
				}
			}
			return $R;
		}


	}