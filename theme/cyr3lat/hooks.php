<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 18:32
	 */

	add_filter('sanitize_title', '\\theme\\cyr3lat::ctl_sanitize_title', 9);
	add_filter('sanitize_file_name', '\\theme\\cyr3lat::ctl_sanitize_title');
	register_activation_hook(__FILE__, '\\theme\\cyr3lat::ctl_schedule_conversion');