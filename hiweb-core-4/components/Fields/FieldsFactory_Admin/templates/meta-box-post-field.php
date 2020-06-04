<?php
	/**
	 * @var \hiweb\fields\field $field
	 */

?>
<div class="<?= hiweb\fields\forms::get_fieldset_classes( $field ) ?>">
	<?php if( $field->FORM()->show_labels() && $field->label() != '' ){ ?><p class="post-attributes-label-wrapper"><label for="<?= $field->global_id() ?>" class="post-attributes-label"><?= $field->label() ?></label></p><?php } ?>
	<!--input-->
	<?php if( $field->FORM()->show_labels() && $field->description() != '' ){ ?><p class="description"><?= $field->description() ?></p><?php } ?>
</div>
