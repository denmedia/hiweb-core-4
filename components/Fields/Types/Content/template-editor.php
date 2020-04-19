<?php
	
	/**
	 * @var Field_Content $Field
	 */
	
	use hiweb\components\Fields\Types\Content\Field_Content;
	
	
	ob_start();
	wp_editor( $value, $rand_id, [
		'_content_editor_dfw' => true,
		'drag_drop_upload' => true,
		'tabfocus_elements' => 'content-html,save-post',
		'editor_height' => $Field->Options()->editor_height(),
		'tinymce' => [
			'resize' => false,
			'wp_autoresize_on' => true,
			'add_unload_trigger' => false,
			'wp_keep_scroll_position' => true,
		]
	] );
	echo preg_replace( '/(name="' . $rand_id . '")/', 'name="' . $name . '"', ob_get_clean() );
