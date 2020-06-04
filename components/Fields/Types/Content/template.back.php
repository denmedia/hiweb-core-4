<?php
	
	use hiweb\core\Paths\PathsFactory;
	use hiweb\core\Strings;
	
	
	$rand_id = Strings::rand( 10 );

?>
<div class="hiweb-field-type-content" data-rand-id="<?= $rand_id ?>" data-baseurl="<?= PathsFactory::root()->url()->get() ?>">
	<div id="wp-<?= $rand_id ?>-wrap" class="wp-core-ui wp-editor-wrap tmce-active has-dfw">
		<div id="wp-<?= $rand_id ?>-editor-tools" class="wp-editor-tools hide-if-no-js">
			<div id="wp-<?= $rand_id ?>-media-buttons" class="wp-media-buttons">
				<?php do_action( 'media_buttons', $rand_id ); ?>
			</div>
			<div class="wp-editor-tabs">
				<button type="button" id="<?= $rand_id ?>-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="<?= $rand_id ?>">Визуально</button>
				<button type="button" id="<?= $rand_id ?>-html" class="wp-switch-editor switch-html" data-wp-editor-id="<?= $rand_id ?>">Текст</button>
			</div>
		</div>
		<div id="wp-<?= $rand_id ?>-editor-container" class="wp-editor-container">
			<div id="qt_<?= $rand_id ?>_toolbar" class="quicktags-toolbar"></div>
			<textarea class="wp-editor-area" data-rand-id="<?= $rand_id ?>" id="<?= $rand_id ?>" style="height: 300px" autocomplete="off" cols="40" data-rand-id="<?= $rand_id ?>"><?= $value ?></textarea></div>
	</div>
</div>