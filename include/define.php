<?php

use hiweb\core\Paths\PathsFactory;


if ( !defined('HIWEB_DIR')) define('HIWEB_DIR', dirname(__DIR__));
if ( !defined('HIWEB_URL')) define('HIWEB_URL', PathsFactory::get(HIWEB_DIR)->url()->get_clear());
if ( !defined('HIWEB_DIR_CORE')) define('HIWEB_DIR_CORE', HIWEB_DIR . '/core');
if ( !defined('HIWEB_DIR_COMPONENTS')) define('HIWEB_DIR_COMPONENTS', HIWEB_DIR . '/components');
if ( !defined('HIWEB_DIR_ASSETS')) define('HIWEB_DIR_ASSETS', HIWEB_DIR . '/assets');
if ( !defined('HIWEB_URL_ASSETS')) define('HIWEB_URL_ASSETS', HIWEB_URL . '/assets');
if ( !defined('HIWEB_DIR_VENDORS')) define('HIWEB_DIR_VENDOR', HIWEB_DIR . '/vendor');
if ( !defined('HIWEB_URL_VENDORS')) define('HIWEB_URL_VENDORS', HIWEB_URL . '/vendor');

///define wp constants if not defined...
if ( !defined('ABSPATH')) define('ABSPATH', PathsFactory::get_root_path().'/');
if ( !defined('WP_CONTENT_DIR')) define('WP_CONTENT_DIR', ABSPATH . '/wp-content');
if ( !defined('WP_CONTENT_URL')) define('WP_CONTENT_URL', PathsFactory::get_url(WP_CONTENT_DIR)->get());