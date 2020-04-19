<?php
	/**
	 * @var \hiweb\fields\field $field
	 */
	
	use hiweb\components\Fields\FieldsAdminFactory;
	
	
	$Field = FieldsAdminFactory::get_the_field();

?>
<fieldset data-id="<?= $Field->ID() ?>" data-global-id="<?= $Field->global_ID() ?>">
	<?php
		if( $Field->Options()->label() != '' ){
			?>
			<div class="post-attributes-label-wrapper"><label class="post-attributes-label" for="<?= FieldsAdminFactory::get_field_input_name( $Field ) ?>"><?= $Field->Options()->label() ?></label></div>
			<?php
		}
	?>
	<?= $Field->get_admin_html( FieldsAdminFactory::get_the_field_value(), FieldsAdminFactory::get_the_field_name() ) ?>
	<?php
		if( $Field->Options()->description() != '' ){
			?>
			<p class="description"><?= $Field->Options()->description() ?></p>
			<?php
		}
	?>
</fieldset>