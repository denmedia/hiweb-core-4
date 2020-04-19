<?php
	/**
	 * @var Field_Date $this
	 */
	
	use hiweb\components\Fields\Types\Date\Field_Date;
	use hiweb\components\FontAwesome\FontAwesomeFactory; ?>
<div class="hiweb-field-type-date">
	<div class="ui action input">
		<input type="text" placeholder="<?= $this->Options()->placeholder() ?>" name="<?= htmlentities($name) ?>" value="<?= htmlentities($value) ?>">
		<button class="button" data-datepicker-show>
			<?= FontAwesomeFactory::get('far fa-calendar-alt')?>
		</button>
		
		<div class="ui flowing popup top center transition hidden">
			<div data-calendarpicker="1"></div>
		</div>
	
	</div>
</div>
