<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 22/11/2018
	 * Time: 11:36
	 * Description: Этот файл используеться для включения моментального кэша страниц сайта
	 */

	require_once __DIR__ . '{hiweb-theme-pages-cache-direct}';

	\theme\pages_cache\pages_cache_direct::the_start();

	/** Подключение родного файла WP */
	require_once __DIR__ . '/index.php';

	\theme\pages_cache\pages_cache_direct::the_end();