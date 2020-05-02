<?php
	/**
	 * @var \hiweb\fields\field $field
	 */
	
	use hiweb\components\Fields\FieldsFactory_Admin;
	
	
	$Field = FieldsFactory_Admin::get_the_field();

?>
<div class="hiweb-fieldset" data-id="<?= $Field->ID() ?>" data-global-id="<?= $Field->global_ID() ?>">
	<?php
		if( $Field->Options()->label() != '' ){
			?>
			<div class="post-attributes-label-wrapper"><label class="post-attributes-label" for="<?= FieldsFactory_Admin::get_field_input_name( $Field ) ?>"><?= $Field->Options()->label() ?></label></div>
			<?php
		}
	?>
	<?= $Field->get_admin_html( FieldsFactory_Admin::get_the_field_value(), FieldsFactory_Admin::get_the_field_name() ) ?>
	<?php
		if( $Field->Options()->description() != '' ){
			?>
			<p class="description"><?= $Field->Options()->description() ?></p>
			<?php
		}
	?>
</div>