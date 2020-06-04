<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 10:40
	 */

	namespace theme;


	class error_404{

		static $admin_menu_slug = 'error-404';
		static $admin_menu_parent = 'themes.php';

		static function init(){
			require_once __DIR__.'/options.php';
		}

	}