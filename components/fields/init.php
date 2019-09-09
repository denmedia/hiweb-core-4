<?php

	///INCLUDE TYPES
	$scan_dir = __DIR__ . '/types';
	if( file_exists( $scan_dir ) && is_dir( $scan_dir ) && is_readable( $scan_dir ) ){
		foreach( scandir( $scan_dir ) as $type_dir ){
			if( preg_match( '/(\.|\.\.)/', $type_dir ) > 0 ) continue;
			$path = $scan_dir . '/' . $type_dir;
			if( !is_dir( $path ) ) continue;
			$include_array = [ 'hooks.php', 'init.php', 'global_functions.php' ];
			foreach( $include_array as $fileName ){
				$filePath = $path . '/' . $fileName;
				if( is_file( $filePath ) && is_readable( $filePath ) ){
					require_once $filePath;
				}
			}
		}
	}