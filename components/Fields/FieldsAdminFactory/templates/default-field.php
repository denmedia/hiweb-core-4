<?php
	/**
	 * @var \hiweb\fields\field $field
	 */

	use hiweb\components\Fields\FieldsAdminFactory;


	$Field = FieldsAdminFactory::get_the_field();

?>
<fieldset>
	<?php
		if( $Field->Options()->label() != '' ){
			?>
			<div class="post-attributes-label-wrapper"><label class="post-attributes-label" for="<?= FieldsAdminFactory::get_field_input_name( $Field ) ?>"><?= $Field->Options()->label() ?></label></div>
			<?php
		}
	?>
	<!--input-->
	<?php
		if( $Field->Options()->description() != '' ){
			?>
			<p class="description"><?= $Field->Options()->description() ?></p>
			<?php
		}
	?>
</fieldset>