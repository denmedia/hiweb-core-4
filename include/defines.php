<?php

	if( !defined( 'HIWEB_THEME_DIR' ) )
		define( 'HIWEB_THEME_DIR', dirname( __DIR__ ) );
	if( !defined( 'HIWEB_THEME_URL' ) )
		define( 'HIWEB_THEME_URL', get_path( HIWEB_THEME_DIR )->get_url() );
	if( !defined( 'HIWEB_THEME_ASSETS_DIR' ) )
		define( 'HIWEB_THEME_ASSETS_DIR', HIWEB_THEME_DIR . '/assets' );
	if( !defined( 'HIWEB_THEME_ASSETS_URL' ) )
		define( 'HIWEB_THEME_ASSETS_URL', get_path( HIWEB_THEME_ASSETS_DIR )->get_url() );
	if( !defined( 'HIWEB_THEME_VENDORS_DIR' ) )
		define( 'HIWEB_THEME_VENDORS_DIR', HIWEB_THEME_DIR . '/vendors' );
	if( !defined( 'HIWEB_THEME_VENDORS_URL' ) )
		define( 'HIWEB_THEME_VENDORS_URL', get_path( HIWEB_THEME_VENDORS_DIR )->get_url() );
	if( !defined( 'HIWEB_THEME_INCLUDE_DIR' ) )
		define( 'HIWEB_THEME_INCLUDE_DIR', HIWEB_THEME_DIR . '/include' );
	if( !defined( 'HIWEB_THEME_CLASSES_DIR' ) )
		define( 'HIWEB_THEME_CLASSES_DIR', HIWEB_THEME_DIR . '/theme' );
	if( !defined( 'HIWEB_THEME_WIDGETS_DIR' ) )
		define( 'HIWEB_THEME_WIDGETS_DIR', HIWEB_THEME_DIR . '/widgets' );
	if( !defined( 'HIWEB_THEME_PARTS' ) )
		define( 'HIWEB_THEME_PARTS', 'parts' );