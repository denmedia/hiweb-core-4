<?php

namespace hiweb\components\Fields\Field_Options;


use hiweb\core\Options\Options;


/**
 * Class for set field location to options (admin menu) page
 *
 * @package hiweb\components\Fields\Field_Options
 * @version 1.0
 */
class Field_Options_Location_AdminMenu extends Options {

	public function page_slug($set = null) {
		return $this->_( 'page_slug', $set );
	}

}