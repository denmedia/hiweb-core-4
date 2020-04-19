<?php
	
	use hiweb\components\Fields\Types\Text\Field_Text;
	/**
	 * @var Field_Text $this
	 */
?>
<div class="hiweb-field-type-text"><input type="text" name="<?= htmlentities( $this->get_sanitize_admin_name($name) ) ?>" value="<?= htmlentities( $this->get_sanitize_admin_value( $value ) ) ?>"/></div>