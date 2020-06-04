<?php
	/**
	 * @var field $field
	 */
?>
<tr class="<?= hiweb\fields\forms::get_fieldset_classes( $field ) ?>">
	<?php if( $field->FORM()->show_labels() ){
		?>
		<th>
			<?php if( $field->label() != '' ){ ?><label for="<?= $field->INPUT()->global_id() ?>"><?= $field->label() ?></label><?php } ?>
		</th>
		<td>
			<!--input-->
			<?php if( $field->description() != '' ){ ?><p class="description"><?= $field->description() ?></p><?php } ?>
		</td>
		<?php
	} else {
		?>
		<td colspan="2">
			<!--input-->
		</td>
		<?php
	} ?>

</tr>