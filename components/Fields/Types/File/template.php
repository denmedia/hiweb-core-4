<?php
	
	/**
	 * @var Field_File $this
	 */
	
	use hiweb\components\Fields\Types\File\Field_File;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	
	
	$rand_id = 'hiweb_field_file_' . \hiweb\core\Strings::rand( 5 );
	$has_file = intval( $value ) > 0 && $this->the_File()->is_exists();
	
	$file_info = 'select file...';
	if( $has_file ){
		$file_info = $this->the_File()->get_basename() . ', size:' . $this->the_File()->get_size_formatted();
	}

?>
<div class="hiweb-field-type-file" data-rand-id="<?= $rand_id ?>" data-global-id="<?= $this->global_id() ?>" data-has-file="<?= $has_file ? '1' : '0' ?>" data-file-mime="<?= $this->the_File()->get_mime_content_type() ?>" data-file-image="<?= $this->the_File()->is_image() ? 'image' : 'file' ?>">
	<input type="hidden" value="<?= htmlentities( $value ) ?>" name="<?= $name ?>"/>
	<div data-status="empty">
		<input type="text" disabled data-message="empty" value="<?= htmlentities( $this->options()->label_empty() ) ?>"/>
		<button data-click-wp-media class="button button-primary" title="<?= htmlentities( $this->options()->label_button_select() ) ?>"><?= FontAwesomeFactory::get( '<i class="fad fa-folder-open"></i>' ) ?></button>
	</div>
	<div data-status="selected">
		<input type="text" disabled data-message="file" value="<?= $file_info ?>"/>
		<div class="button-group">
			<button data-click-wp-media class="button" title="<?= __( 'Select other', 'hiweb-core-4' ) ?>"><?= FontAwesomeFactory::get( '<i class="fad fa-folder-open"></i>' ) ?></button>
			<a href="<?= get_edit_post_link( $value ) ?>" target="_blank" data-click-edit-attachment class="button" title="<?= __( 'Edit attachment', 'hiweb-core-4' ) ?>"><?= FontAwesomeFactory::get( '<i class="fas fa-file-edit"></i>' ) ?></a>
			<a href="<?= $this->the_File()->url()->get() ?>" target="_blank" class="button button-secondary" title="<?= __( 'Download the file', 'hiweb-core-4' ) ?>"><?= FontAwesomeFactory::get( 'download' ) ?></a>
			<button data-click-clear class="button button-link-delete" title="<?= __( 'Unselect file (clear)', 'hiweb-core-4' ) ?>"><?= FontAwesomeFactory::get( '<i class="fad fa-file-times"></i>' ) ?></button>
		</div>
	</div>
	<div data-upload-place>
		<?= __( 'Drag&Drop uploaded file in that place', 'hiweb-core-4' ) ?>
	</div>
</div>