<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 20:38
	 */


	if(!function_exists('the_breadcrumbs')){

		function the_breadcrumbs($class = ''){
			\theme\breadcrumbs::$class = $class;
			\theme\breadcrumbs::the();
		}

	}