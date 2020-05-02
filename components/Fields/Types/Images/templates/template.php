<?php
	/**
	 * @var Field_Images $this
	 * @var string       $name
	 */
	
	use hiweb\components\Fields\Types\Images\Field_Images;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	use hiweb\components\Images\ImagesFactory;
	
	
	$value_sanitized = $this->get_sanitize_admin_value( $value );
	
	$count_id = 'low';
	if( count( $value_sanitized ) > 24 ){
		$count_id = 'many';
	}
	elseif( count( $value_sanitized ) > 12 ){
		$count_id = 'medium';
	}
	
	$rand_id = \hiweb\core\Strings::rand( 5 );
	
	$attributes = new \hiweb\core\ArrayObject\ArrayObject( [
		'data-images-count' => count( $value_sanitized ),
		'data-images-count-id' => $count_id,
		'data-name' => $name
	] );

?>
<div class="hiweb-field-type-images" <?= $attributes->get_param_html_tags() ?>>
	<div class="images-top-panel">
		<div class="images-top-panel-label"><?= $this->Options()->label_top() ?> : <b data-images-count-wrap><?= count( $value_sanitized ) ?></b></div>
		<div data-constrol-wrap>
			<a href="" data-click="add" data-add-index="-1"><?= FontAwesomeFactory::get( '<i class="fad fa-plus-circle"></i>' ) ?></a>
			<!--<a href="" data-click="revert"><?= FontAwesomeFactory::get( '<i class="fad fa-sync-alt"></i>' ) ?></a>-->
			<a href="" data-click="shuffle"><?= FontAwesomeFactory::get( '<i class="fad fa-random"></i>' ) ?></a>
			<a href="" data-click="clear"><?= FontAwesomeFactory::get( '<i class="fad fa-trash"></i>' ) ?></a>
		</div>
	</div>
	<div data-message="empty">
		<?= __( 'No images, press "+" for select and adding first file', 'hiweb-core-4' ) ?>
	</div>
	<?php $this->the_item( $name ) ?>
	<div data-images-wrap>
		<?php $this->the_item_plus( 0 ); ?>
		<?php
			foreach( $value_sanitized as $image_id ){
				$image = ImagesFactory::get( $image_id );
				$this->the_item( $name, $image_id );
			}
		?>
		<?php $this->the_item_plus( 1 ); ?>
	</div>
</div>