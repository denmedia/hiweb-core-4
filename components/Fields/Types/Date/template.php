<?php
	/**
	 * @var Field_Date $this
     * @var string $name
     * @var mixed $value
	 */
	
	use hiweb\components\Fields\Types\Date\Field_Date;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	
	$rand_id = \hiweb\core\Strings::rand();
	
	?>
<div <?=$this->get_admin_wrap_tag_properties([], $name)?>>
	<input type="text" placeholder="<?= $this->options()->placeholder() ?>" name="<?= htmlentities($name) ?>" value="<?= htmlentities($value) ?>" data-field-rand-id="<?=$rand_id?>">
	<button class="button" data-datepicker-show>
		<?= FontAwesomeFactory::get('far fa-calendar-alt')?>
	</button>
	
	<div style="display: none;">
		<div data-calendarpicker="0" data-field-rand-id="<?=$rand_id?>" class="hiweb-fields_date-picker">
			<span data-calendarpicker-arrow="left"><?=FontAwesomeFactory::get('fa-arrow-left')?></span>
			<span data-calendarpicker-arrow="right"><?=FontAwesomeFactory::get('fa-arrow-right')?></span>
		</div>
	</div>
</div>