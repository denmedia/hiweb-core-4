<?php

	use hiweb\components\Fields\FieldsAdminFactory;


	$field_name = FieldsAdminFactory::get_the_field_name();
?>
<div class="hiweb-field-type-content" data-field-id="<?= $field_name ?>"><?php

		wp_editor( FieldsAdminFactory::get_the_field_value(), $field_name, [
			'_content_editor_dfw' => true,
			'drag_drop_upload' => true,
			'tabfocus_elements' => 'content-html,save-post',
			'editor_height' => 300,
			'tinymce' => [
				'resize' => false,
				'wp_autoresize_on' => $_wp_editor_expand,
				'add_unload_trigger' => false,
				'wp_keep_scroll_position' => !$is_IE,
			]
		] );

	?></div>