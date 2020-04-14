<?php

	$Field = \hiweb\components\Fields\FieldsAdminFactory::get_the_field();
	$value = \hiweb\components\Fields\FieldsAdminFactory::get_the_field_value();
	$name = \hiweb\components\Fields\FieldsAdminFactory::get_the_field_name();

?>
<div class="hiweb-field-type-text"><input type="text" name="<?= htmlentities( $name ) ?>" value="<?= htmlentities( $Field->get_sanitize_admin_value( $value ) ) ?>"/></div>