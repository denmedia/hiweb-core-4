<?php
	
	/**
	 * @var Field_Image $this
	 */
	
	use hiweb\components\Fields\Types\Image\Field_Image;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	
	
	$rand_id = 'hiweb_field_file_' . \hiweb\core\Strings::rand( 5 );
	$has_file = intval( $value ) > 0 && $this->the_Image()->is_exists();
	
	$file_info = 'select file...';
	if( $has_file ){
		$file_info = $this->the_Image()->path()->file()->basename() . ', size:' . $this->the_Image()->path()->file()->get_size_formatted();
	}
	$style = new \hiweb\core\ArrayObject\ArrayObject();
	$style2 = new \hiweb\core\ArrayObject\ArrayObject();
	$image_width = strpos($this->options()->admin_width(),'%') !== false ? 1024 : intval($this->options()->admin_width());
	$image_height = intval($this->options()->admin_height());
	if($this->the_Image()->is_exists()) {
		$style2->push('background-image','url('.$this->the_Image()->get_src( [$image_width, $image_height] ).')');
	}
	$style->push('width', $this->options()->admin_width());
	$style->push('height', $this->options()->admin_height());
?>
<div class="hiweb-field-type-image" data-rand-id="<?= $rand_id ?>" data-global-id="<?= $this->global_id() ?>" data-has-file="<?= $has_file ? '1' : '0' ?>" data-file-mime="<?= $this->the_Image()->get_mime_type() ?>" style="<?=$style->get_param_html_style()?>">
	<div data-image-place style="<?=$style2->get_param_html_style()?>"></div>
	<input type="hidden" value="<?= htmlentities( $value ) ?>" name="<?= $name ?>"/>
	<!--CONTROL-->
	<div data-image-control-wrap="0">
		<a data-click="select" href="#"><?= FontAwesomeFactory::get( '<i class="fad fa-folder-open"></i>' ) ?></a>
	</div>
	<div data-image-control-wrap="1">
		<a data-click="edit" href="#"><?= FontAwesomeFactory::get( '<i class="fad fa-file-check"></i>' ) ?></a>
		<a data-link="edit_link" href="<?= get_edit_post_link( $this->the_Image()->get_attachment_ID() ) ?>" target="_blank"><?= FontAwesomeFactory::get( '<i class="fas fa-file-edit"></i>' ) ?></a>
		<a data-link="url" href="<?= wp_get_attachment_url( $this->the_Image()->get_attachment_ID() ) ?>" target="_blank"><?= FontAwesomeFactory::get( '<i class="fad fa-file-download"></i>' ) ?></a>
		<a data-click="remove" href="#"><?= FontAwesomeFactory::get( '<i class="fas fa-file-times"></i>' ) ?></a>
	</div>
</div>