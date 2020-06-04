<?php
	/**
	 * @var int $plus
	 */
	
	use hiweb\components\FontAwesome\FontAwesomeFactory;


?>
<div data-image-plus="<?= $plus ?>">
	<div class="over-wrap">
		<a href="#" data-click="add" data-add-index="<?= $plus == 0 ? '0' : '-1' ?>">
			<?= FontAwesomeFactory::get( '<i class="fad fa-plus-circle"></i>' ) ?>
		</a>
	</div>
</div>