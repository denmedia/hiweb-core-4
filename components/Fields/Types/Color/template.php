<?php
	
	use hiweb\components\Fields\Types\Color\Field_Color;
	/**
	 * @var Field_Color $this
	 */
?>
<div class="hiweb-field-type-color"><input type="text" name="<?= htmlentities( $this->get_sanitize_admin_name($name) ) ?>" value="<?= htmlentities( $this->get_sanitize_admin_value( $value ) ) ?>"/></div>