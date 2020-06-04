<?php
	
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	use hiweb\core\Strings;
	
	
	$rand_id = 'hiweb_field_fontawesome_' . Strings::rand( 5 );
	$FontAwesome = FontAwesomeFactory::get( $value );
	$FontAwesome_unknown = FontAwesomeFactory::get( 'question' );
	$FontAwesome_loader = FontAwesomeFactory::get( 'fas fa-spinner-third' );
?>
<div class="hiweb-field-type-fontawesome" data-rand-id="<?= $rand_id ?>" data-selected="<?= $FontAwesome->is_exists() ? '1' : '0' ?>">
	
	<div class="button-group">
		<div data-icon-preview><?= $FontAwesome->is_exists() ? $FontAwesome : $FontAwesome_unknown ?></div>
		<input type="text" name="<?= $name ?>" value="<?= htmlentities( $value ) ?>"/>
		<button data-fontawesome-click="all" class="button button-primary"><?= FontAwesomeFactory::get( '<i class="fad fa-icons"></i>' ) ?></button>
		<button data-fontawesome-click="styles" class="button"><?= FontAwesomeFactory::get( '<i class="fad fa-palette"></i>' ) ?></button>
		<button data-fontawesome-click="clear" class="button button-link-delete"><?= FontAwesomeFactory::get( '<i class="fad fa-times-circle"></i>' ) ?></button>
	</div>
	<div style="display: none">
		<div data-fontawesome-icon-unknown><?= $FontAwesome_unknown ?></div>
		<div data-fontawesome-icon-loader><span class="loader-rotate"><?= $FontAwesome_loader ?></span></div>
		<div data-fontawesome-result-place="<?= $rand_id ?>">result</div>
		<div data-fontawesome-styles-place="<?= $rand_id ?>">styles</div>
	</div>

</div>
