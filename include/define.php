<?php

	if( !defined( 'HIWEB_DIR' ) ) define( 'HIWEB_DIR', dirname( __DIR__ ) );
	if( !defined( 'HIWEB_URL' ) ) define( 'HIWEB_URL', \hiweb\core\Paths\PathsFactory::get( HIWEB_DIR )->url()->get_clear() );
	if( !defined( 'HIWEB_DIR_CORE' ) ) define( 'HIWEB_DIR_CORE', HIWEB_DIR . '/core' );
	if( !defined( 'HIWEB_DIR_COMPONENTS' ) ) define( 'HIWEB_DIR_COMPONENTS', HIWEB_DIR . '/components' );
	if( !defined( 'HIWEB_DIR_ASSETS' ) ) define( 'HIWEB_DIR_ASSETS', HIWEB_DIR . '/assets' );
	if( !defined( 'HIWEB_URL_ASSETS' ) ) define( 'HIWEB_URL_ASSETS', HIWEB_URL . '/assets' );
	if( !defined( 'HIWEB_DIR_VENDORS' ) ) define( 'HIWEB_DIR_VENDOR', HIWEB_DIR . '/vendor' );
	if( !defined( 'HIWEB_URL_VENDORS' ) ) define( 'HIWEB_URL_VENDORS', HIWEB_URL . '/vendor' );