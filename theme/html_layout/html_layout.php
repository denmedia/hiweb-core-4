<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 09:55
	 */

	namespace theme;

	require_once __DIR__.'/hooks.php';


	use theme\html_layout\tags\body;
	use theme\html_layout\tags\head;
	use theme\html_layout\tags\html;


	class html_layout{

		static function init(){
			require_once __DIR__.'/hooks.php';
		}

		/**
		 * Print <!DOCTYPE><html><head>...</head><body ...>
		 */
		static function the_before(){
			html::the_before();
			head::the();
			body::the_before();
		}


		/**
		 * Print </body></html>
		 */
		static function the_after(){
			body::the_after();
			html::the_after();
		}

	}