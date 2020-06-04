<?php

	namespace theme\htaccess;


	use hiweb\core\Paths\PathsFactory;
	use theme\htaccess;


	class injector{

		static private $htaccess_prefix = '#hiweb theme htaccess';


		static function setup(){
			$htaccess = PathsFactory::get_file( '/.htaccess' );
			if( $htaccess->is_writable() ){
				$htaccess_content = $htaccess->get_content();
				if( preg_match( '/(?<marker>#hiweb theme htaccess (?>start|end))/im', $htaccess_content ) < 1 ){
					$htaccess->set_content( self::get_templates_content(), true );
				}
			}
		}


		/**
		 * @return string
		 */
		static function get_templates_content(){
			$R = [];
			foreach( htaccess::$template_files as $template_name ){
				$path = PathsFactory::get_file( dirname( __DIR__ ) . '/templates/' . $template_name );
				if( $path->is_readable() ){
					$R[] = strtr( $path->get_content(), [
						'{mod_expires_time}' => htaccess::$mod_expires_time,
						'{mod_expires_time_default}' => htaccess::$mod_expires_time_default
					] );
				}
			}
			return "\n\n" . self::$htaccess_prefix . " start\n\n" . join( "\n\n", $R ) . "\n\n" . self::$htaccess_prefix . " end\n";
		}

	}