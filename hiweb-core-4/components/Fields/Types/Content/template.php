<?php
	/**
	 * @var Field_Content $this
	 * @var string|null   $value
	 */
	
	use hiweb\components\Fields\Types\Content\Field_Content;
	$rand_id = \hiweb\core\Strings::rand();
	?>
<div class="hiweb-field-type-content" data-rand-id="<?= $rand_id ?>" data-field-id="<?=$this->id()?>" data-field-global-id="<?= $this->global_ID() ?>">
	<textarea name="<?= $name ?>" data-rand-id="<?= $rand_id ?>"><?= $value ?></textarea>
</div>