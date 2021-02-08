<?php
	/**
	 * @var \hiweb\components\Fields\Types\Map_Yandex\Field_MapYandex $this
     * @var string $name
     * @var mixed $value
	 */
	use hiweb\core\Strings;
	
	
	$rand_id = Strings::rand();
	
?>
<div <?=$this->get_admin_wrap_tag_properties([], $name)?> id="<?= $rand_id ?>" style="min-height: 400px">
	<input type="hidden" name="<?= $name ?>[]" value="<?= $value[0] ?>" data-long/>
	<input type="hidden" name="<?= $name ?>[]" value="<?= $value[1] ?>" data-lat/>
	<input type="hidden" name="<?= $name ?>[]" value="<?= $value[2] ?>" data-zoom/>
	<div class="hiweb-field-map-yandex-place" id="<?= $rand_id ?>-map"></div>
</div>