<?php

	add_action('init', function(){
		///CLEAR CACHE FILES
		\hiweb\core\Cache\CacheFactory::clear_old_files();
	});
