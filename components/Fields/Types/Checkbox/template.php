<?php
	
	use hiweb\components\Fields\Types\Checkbox\Field_Checkbox;
	
	
	/** @var Field_Checkbox $this */
	$value = $this->get_sanitize_admin_value( $value );

?>
<div <?= $this->get_admin_wrap_tag_properties([], $name) ?>>
	<input class="checkbox" type="checkbox" name="<?= $this->get_sanitize_admin_name( $name ) ?>" <?= $value ? 'checked="checked"' : '' ?>>
	<label><?= $this->options()->label_checkbox() ?></label>
</div>