<?php
	/**
	 * @var \hiweb\fields\field $field
	 */
	
	use hiweb\components\Fields\FieldsFactory_Admin;
	
	
	$Field = FieldsFactory_Admin::get_the_field();

?>
<div class="hiweb-fieldset" data-id="<?= $Field->ID() ?>" data-global-id="<?= $Field->global_ID() ?>">
	<?php
		if( $Field->options()->label() != '' || $Field->options()->tooltip_help()['text'] != '' || $Field->options()->tooltip_help()['image'] != '' ){
			?>
			<div class="post-attributes-label-wrapper">
				<label class="post-attributes-label" for="<?= FieldsFactory_Admin::get_field_input_name( $Field ) ?>"><?= $Field->options()->label() ?></label>
				<?php
					if( $Field->options()->tooltip_help()['text'] != '' || $Field->options()->tooltip_help()['image'] != '' ){
						$tooltip_html = '';
						if( $Field->options()->tooltip_help()['image'] != '' ){
							$path = \hiweb\core\Paths\PathsFactory::get( $Field->options()->tooltip_help()['image'] );
							if( $path->is_local() && $path->file()->is_exists() ){
								$text_exists = $Field->options()->tooltip_help()['text'] != '';
								$tooltip_html .= '<div class="help-image-wrap'.( $text_exists ? '' : ' help-only-image-wrap').'"><img class="help-image" src="' . $path->get_url() . '"/></div>';
							}
						}
						if( $Field->options()->tooltip_help()['text'] != '') {
							$tooltip_html .= '<div class="help-text">' . $Field->options()->tooltip_help()['text'] . '</div>';
						}
						$tooltip_html = '<div class="hiweb-fields-tooltip-help">' . $tooltip_html . '</div>';
						?>
						<span class="hiweb-fieldset-help" data-hiweb-fields-tooltip-help="<?= htmlentities( $tooltip_html ) ?>"><?= \hiweb\components\FontAwesome\FontAwesomeFactory::get( $Field->options()->tooltip_help()['icon'] ) ?></span>
						<?php
					}
				?>
			</div>
			<?php
		}
	?>
	<?= $Field->get_admin_html( FieldsFactory_Admin::get_the_field_value(), FieldsFactory_Admin::get_the_field_name() ) ?>
	<?php
		if( $Field->options()->description() != '' ){
			?>
			<p class="description"><?= $Field->options()->description() ?></p>
			<?php
		}
	?>
</div>