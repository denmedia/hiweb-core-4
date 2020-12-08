<?php

namespace hiweb\components\Fields\Field_Options;


use hiweb\core\Options\Options;


class Field_Options_Location_Customize_Sections extends Options {

	public function __construct($parent_OptionsObject = null) {
		parent::__construct( $parent_OptionsObject );
	}


	/**
	 * @param null $set
	 *
	 * @return array|Field_Options_Location_Customize_Sections|mixed|null
	 */
	public function title($set = null) {
		return $this->_( 'title', $set );
	}


	/**
	 * @param null|int $set
	 *
	 * @return array|Field_Options_Location_Customize_Sections|mixed|null
	 */
	public function priority($set = null) {
		return $this->_( 'priority', $set );
	}


	/**
	 * @param null|string|string[] $set
	 *
	 * @return array|Field_Options_Location_Customize_Sections|mixed|null
	 */
	public function capability($set = null) {
		return $this->_( 'capability', $set );
	}


	/**
	 * @param null|string $set
	 *
	 * @return array|Field_Options_Location_Customize_Sections|mixed|null
	 */
	public function description($set = null) {
		return $this->_( 'description', $set );
	}

}