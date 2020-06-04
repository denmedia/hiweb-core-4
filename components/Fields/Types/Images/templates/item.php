<?php
	
	/**
	 * @var Field_Images $this
	 * @var string $name
	 * @var int    $attachment_id
	 */
	
	use hiweb\components\Fields\Types\Images\Field_Images;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	use hiweb\components\Images\ImagesFactory;
	
	
	$Image = ImagesFactory::get( $attachment_id );
	
?>
<div <?= $attachment_id == 0 ? 'data-source-image' : 'data-item-image="' . $attachment_id . '"' ?> data-image-exists="<?= $Image->is_attachment_exists() ?>" style="background-image: url(<?= $Image->get_src( 'medium' ) ?>)">
	<input type="hidden" name="<?= $name ?>[]" value="<?= $attachment_id ?>"/>
	<!--CONTROL-->
	<div data-image-control-wrap>
		<a data-click="edit" href="#"><?= FontAwesomeFactory::get( '<i class="fad fa-file-check"></i>' ) ?></a>
		<a data-link="edit_link" href="<?= get_edit_post_link( $attachment_id ) ?>" target="_blank"><?= FontAwesomeFactory::get( '<i class="fas fa-file-edit"></i>' ) ?></a>
		<a data-link="url" href="<?= wp_get_attachment_url( $attachment_id ) ?>" target="_blank"><?= FontAwesomeFactory::get( '<i class="fad fa-file-download"></i>' ) ?></a>
		<a data-click="remove" href="#"><?= FontAwesomeFactory::get( '<i class="fas fa-file-times"></i>' ) ?></a>
	</div>
</div>