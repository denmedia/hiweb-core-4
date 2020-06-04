<?php

	namespace hiweb\core\Cache;


	use hiweb\core\Strings;


	/**
	 * @param string $variable_name
	 * @param null   $group_name
	 * @return bool|int|string
	 */
	function convert_name_group_to_fileName( $variable_name = '', $group_name = null ){
		return preg_replace( '/^hiweb[\s\-_]/', '', Strings::sanitize_id( $group_name . '-' . $variable_name, '_', 74 ) );
	}