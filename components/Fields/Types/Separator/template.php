<?php
	/**
	 * @var Field_Separator $this
	 */
	
	use hiweb\components\Fields\Types\Separator\Field_Separator;


?>
<div class="hiweb-field-type-separator">
	<?php
		if( $this->Options()->separator_label() != '' ) {
	?>
	<<?= $this->Options()->tag_label() ?> class="hw-field-separator-title<?= $this->Options()->description() != '' ? ' has-description' : ' no-description' ?>"><?= $this->Options()->separator_label() ?></<?= $this->Options()->tag_label() ?>>
<?php
	}
?>
<?php if( $this->Options()->separator_description() != '' ){
	?>
	<<?= $this->Options()->tag_description() ?>  class="hw-field-separator-description"><?= $this->Options()->separator_description() ?></<?= $this->Options()->tag_description() ?>>
	<?php
} ?>
</div>