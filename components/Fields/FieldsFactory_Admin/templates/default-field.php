<?php
	/**
	 * @var \hiweb\fields\field $field
	 */
	
	use hiweb\components\Fields\FieldsFactory_Admin;
	
	
	$Field = FieldsFactory_Admin::get_the_field();

?>
<div class="hiweb-fieldset" data-id="<?= $Field->ID() ?>" data-global-id="<?= $Field->global_ID() ?>">
	<?php
		if( $Field->options()->label() != '' ){
			?>
			<div class="post-attributes-label-wrapper"><label class="post-attributes-label" for="<?= FieldsFactory_Admin::get_field_input_name( $Field ) ?>"><?= $Field->options()->label() ?></label></div>
			<?php
		}
	?>
	<?= $Field->get_admin_html( FieldsFactory_Admin::get_the_field_value(), FieldsFactory_Admin::get_the_field_name() ) ?>
	<?php
		if( $Field->options()->description() != '' ){
			?>
			<p class="description"><?= $Field->options()->description() ?></p>
			<?php
		}
	?>
</div>