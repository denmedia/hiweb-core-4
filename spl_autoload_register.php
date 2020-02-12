<?php

	try{
		spl_autoload_register( function( $class_name ){
			if( $class_name == 'hiweb' ){
				//include_once __DIR__ . '/hiweb.php';
				} elseif( preg_match( '/^hiweb\\\\/i', $class_name ) > 0 ) {
				$class_name = preg_replace( '/^hiweb\\\\/i', '', $class_name );
				$class_paths = [
					__DIR__ . '/' . str_replace( '\\', '/', $class_name ),
					dirname( __DIR__ ) . '/' . str_replace( '\\', '/', $class_name )
				];
				foreach( $class_paths as $class_path ){
					if( file_exists( $class_path . '.php' ) && is_file( $class_path . '.php' ) && is_readable( $class_path . '.php' ) ){
						include_once $class_path . '.php';
					}
					if( file_exists( $class_path ) && is_dir( $class_path ) && is_readable( $class_path ) ){
						foreach( scandir( $class_path ) as $file ){
							if( preg_match( '/^(.){1,2}$/', $file ) > 0 ) continue;
							if( preg_match( '/^-/i', $file ) > 0 ) continue;
							$php_path = $class_path . '/' . $file;
							if( preg_match( '/\.php$/i', $file ) > 0 ){
								include_once $php_path;
							}
						}
					}
				}
			}
		} );
	} catch( Exception $e ){

	}