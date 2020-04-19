<?php

	use hiweb\components\Fields\FieldsAdminFactory;
	use hiweb\components\Fields\Types\Checkbox\Field_Checkbox;
	
	
	/** @var Field_Checkbox $this */
	$rand_id = \hiweb\core\Strings::rand( 5 );
	$value = $this->get_sanitize_admin_value($value);
	
?>
<div class="hiweb-field-checkbox">
	<div class="ui toggle checkbox">
		<input class="checkbox" type="checkbox" id="<?= $rand_id ?>" name="<?= $this->get_sanitize_admin_name( FieldsAdminFactory::get_the_field_name() ) ?>" <?= $value ? 'checked="checked"' : '' ?>>
		<?php
			if( $this->Options()->label_checkbox() != '' ){
				?>
				<label for="<?= $rand_id ?>"><?= $this->Options()->label_checkbox() ?></label>
				<?php
			}
		?>
	</div>
</div>