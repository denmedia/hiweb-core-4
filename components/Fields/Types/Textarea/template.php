<?php
	
	use hiweb\components\Fields\Types\Textarea\Field_Textarea;
	
	
	/**
	 * @var Field_Textarea $this
	 */
?>
<div class="hiweb-field-type-textarea">
	<textarea name="<?=$name?>" rows="<?=$this->Options()->rows()?>"><?=$value?></textarea>
</div>