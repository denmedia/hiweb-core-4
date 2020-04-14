<?php

	use hiweb\components\Fields\FieldsAdminFactory;


	$Field = \hiweb\components\Fields\FieldsAdminFactory::get_the_field();
	$rand_id = \hiweb\core\Strings::rand( 5 );
?>
<div class="hiweb-field-checkbox">
	<div class="ui toggle checkbox">
		<input class="checkbox" type="checkbox" id="<?= $rand_id ?>" name="<?= $Field->get_sanitize_admin_name( FieldsAdminFactory::get_the_field_name() ) ?>" <?= $Field->get_sanitize_admin_value( FieldsAdminFactory::get_the_field_value() ) ? 'checked="checked"' : '' ?>>
		<?php
			if( $Field->Options()->_( 'label_checkbox' ) != '' ){
				?>
				<label for="<?= $rand_id ?>"><?= $Field->Options()->_( 'label_checkbox' ) ?></label>
				<?php
			}
		?>
	</div>
</div>