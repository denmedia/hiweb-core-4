<?php

	namespace hiweb\components\Console;


	/**
	 * Class ConsoleFactory
	 * @package hiweb\components\Console
	 */
	class ConsoleFactory{


		/** @var array|Console[] */
		static $messages = [];
		static $messages_limit = 99;


		/**
		 * @param string $content
		 * @param string $type
		 * @param string $groupTitle
		 * @param array  $additionData
		 * @param bool   $debugStatus
		 * @return Console
		 */
		static function add( $content = '', $type = 'info', $groupTitle = '', $additionData = [], $debugStatus = false ){
			$console = new Console( $content, $type, $additionData );
			$console->set_groupTitle( $groupTitle );
			if( $debugStatus ) {
				$console->set_debugStatus( true );
				$console->addition_data = array_merge( (array)$console->addition_data, ['debug_backtrace' => debug_backtrace()] );
			}
			$global_id = spl_object_hash( $console );
			self::$messages[ $groupTitle ][ $global_id ] = $console;
			return $console;
		}


		/**
		 * Print messages script
		 * @version 1.3
		 */
		static function the(){
			while( self::$messages_limit > 0 && count( self::$messages ) > 0 ){
				$groupTitle = key( self::$messages );
				$messages = array_shift( self::$messages );
				///
				if( $groupTitle != '' ){
					?>
					<script>console.groupCollapsed("%c<?=addslashes( $groupTitle )?>", "color: #888;font-size: 1.2em;");</script><?php
				}
				///
				while( self::$messages_limit > 0 && count( $messages ) > 0 ){
					$message = array_shift( $messages );
					if( $message instanceof Console ) $message->the();
					self::$messages_limit --;
				}
				///
				if( $groupTitle != '' ){
					?>
					<script>console.groupEnd();</script><?php
				}
			}
		}
	}
