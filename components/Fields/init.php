<?php

	namespace hiweb\components\Fields;


	///LOAD TYPES
	use hiweb\core\Paths\PathsFactory;


	$types_path = __DIR__ . '/Types';
	if( !file_exists( $types_path ) || !is_dir( $types_path ) ){
		\hiweb\components\Console\ConsoleFactory::add( 'Can load types directory', 'warn', __NAMESPACE__, $types_path, true );
	} else {
		foreach( scandir( $types_path ) as $type_dir ){
			if( preg_match( '/^(\.){1,2}$/i', $type_dir ) > 0 ) continue;
			$type_dir = $types_path . '/' . $type_dir;
			PathsFactory::get( $type_dir )->File()->include_files( 'php', 'template', 0 );
		}
	}
