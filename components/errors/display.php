<?php

	namespace hiweb\errors;


	use hiweb\context;


	class display{

		static protected $showBacktrace = false;
		static protected $footerErrorsHtml = [];


		/**
		 * @param bool $showBacktrace
		 * @return bool
		 */
		static function enable( $showBacktrace = false ){
			if( !context::is_frontend_page() && !context::is_admin_page() ) return false;
			\hiweb\css( HIWEB_URL_ASSETS . '/css/errors.css' );
			self::$showBacktrace = $showBacktrace;
			@ini_set( 'display_errors', 'off' );
			error_reporting( E_ALL & ~E_NOTICE );
			@ini_set( 'error_reporting', E_ALL );
			if( !defined( 'WP_DEBUG' ) ) define( 'WP_DEBUG', true );
			if( !defined( 'WP_DEBUG_DISPLAY' ) ) define( 'WP_DEBUG_DISPLAY', true );
			set_error_handler( 'hiweb\\errors\\display::errorHandler' );
			//self::errorFatal(); // will die on any error except E_NOTICE
			register_shutdown_function( 'hiweb\\errors\\display::errorFatal' );
			return true;
		}


		/**
		 * @param $errno
		 * @param $errstr
		 * @param $errfile
		 * @param $errline
		 * @version 1.2
		 */
		static function errorHandler( $errno, $errstr, $errfile, $errline ){
			if( preg_match( '/(wp-admin|wp-include)/g', $errfile ) > 0 ) return;
			$errno = $errno & error_reporting();
			if( $errno == 0 ) return;
			if( !defined( 'E_STRICT' ) ) define( 'E_STRICT', 2048 );
			if( !defined( 'E_RECOVERABLE_ERROR' ) ) define( 'E_RECOVERABLE_ERROR', 4096 );
			$r = "<p class='hiweb-core-error-line'><b>";
			switch( $errno ){
				case E_ERROR:
					$r .= "Fatal Error";
					break;
				case E_WARNING:
					$r .= "Warning";
					break;
				case E_PARSE:
					$r .= "Parse Error";
					break;
				case E_NOTICE:
					$r .= "Notice";
					break;
				case E_CORE_ERROR:
					$r .= "Core Error";
					break;
				case E_CORE_WARNING:
					$r .= "Core Warning";
					break;
				case E_COMPILE_ERROR:
					$r .= "Compile Error";
					break;
				case E_COMPILE_WARNING:
					$r .= "Compile Warning";
					break;
				case E_USER_ERROR:
					$r .= "User Error";
					break;
				case E_USER_WARNING:
					$r .= "User Warning";
					break;
				case E_USER_NOTICE:
					$r .= "User Notice";
					break;
				case E_STRICT:
					$r .= "Strict Notice";
					break;
				case E_RECOVERABLE_ERROR:
					$r .= "Recoverable Error";
					break;
				default:
					$r .= "Unknown error ($errno)";
					break;
			}
			$r .= ":</b> <i>" . nl2br( $errstr ) . "</i><br>File: <b><u>$errfile</u></b> on line <b>$errline</b>\n";
			if( self::$showBacktrace && function_exists( 'debug_backtrace' ) ){
				$r .= "<div style='font-size: 10px;'>";
				$backtrace = debug_backtrace();
				array_shift( $backtrace );
				foreach( $backtrace as $i => $l ){
					$r .= "[$i] in function <b>" . ( isset( $l['class'] ) ? "{$l['class']}" : '' ) . "" . ( isset( $l['type'] ) ? "{$l['type']}" : '' ) . "{$l['function']}</b>";
					if( isset( $l['file'] ) ) $r .= " in <b><u>{$l['file']}</u></b>";
					if( isset( $l['line'] ) ) $r .= " on line <b>{$l['line']}</b>";
					$r .= "\n";
				}
				$r .= "</div>";
			}
			$r .= "</p>";
			if( $errno == E_ERROR ) print $r; else self::putToFooter( $r );
		}


		static function errorFatal(){
			$error = error_get_last();
			self::errorHandler( $error["type"], $error["message"], $error["file"], $error["line"] );
		}


		static function putToFooter( $errorHtml ){
			self::$footerErrorsHtml[] = $errorHtml;
			self::$footerErrorsHtml = array_unique( self::$footerErrorsHtml );
		}


		static function echo_footerErrorsHtml(){
			echo implode( '', self::$footerErrorsHtml );
		}
	}