<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 02:12
	 */

	try{
		spl_autoload_register( function( $class ){
			$class_path = str_replace( '\\', '/', substr( $class, strpos( $class, '\\' ) + 1 ) ) . '.php';
			$class_basename = str_replace( '\\', '/', substr( $class, strrpos( $class, '\\' ) + 1 ) );
			$class_filename = basename( $class_basename );
			$search_paths = [
				HIWEB_THEME_CLASSES_DIR . '/' . $class_path,
				HIWEB_THEME_CLASSES_DIR . '/' . $class_basename . '/' . $class_path,
				HIWEB_THEME_DIR . '/' . $class_path,
				HIWEB_THEME_DIR . '/' . $class_filename . '/' . $class_path,
				HIWEB_THEME_WIDGETS_DIR . '/' . $class_path,
				HIWEB_THEME_WIDGETS_DIR . '/' . $class_basename . '/' . $class_filename . '.php'
			];
			foreach( $search_paths as $path ){
				if( file_exists( $path ) ){
					return require_once( $path );
				}
			}
			return false;
		} );
	} catch( Exception $e ){
		\hiweb\console::debug_error( 'Функция [spl_autoload_register] не работает.' );
	}