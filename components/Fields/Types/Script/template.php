<?php
	
	$rand_id = 'hiweb_feild_script_' . \hiweb\core\Strings::rand( 5 );
?>
<div class="hiweb-field-type-script" data-rand-id="<?= $rand_id ?>">
	<textarea name="<?= $name ?>" id><?= $value ?></textarea>
</div>