<?php
	
	/**
	 * @var array $section_data
	 * @var array $field_values
	 */
	
	use hiweb\components\Fields\Types\Tab\Field_Tab;


?>
<div class="hiweb-fields-form-tabs-wrap">
	<div class="hiweb-fields-form-tabs-handles">
		<?php
			/**
			 * @var string     $tab_global_id
			 * @var  Field_Tab $field_tab
			 */
			$index = 0;
			foreach( $section_data['tabs'] as $tab_global_id => $field_tab ){
				?>
				<div data-tab-handle="<?= $tab_global_id ?>" data-tab-active="<?= $index == 0 ? '1' : '0' ?>">
					<div class="label-wrap">
						<?php
							if( $field_tab->options()->icon() != '' ){
								$font_awesome = \hiweb\components\FontAwesome\FontAwesomeFactory::get( $field_tab->options()->icon() );
								if( $font_awesome->is_exists() ){
									?>
									<div class="icon"><?= $font_awesome ?></div>
									<?php
								}
							}
						?>
						<div class="label"><?= $field_tab->options()->label() ?></div>
						<?php if( $field_tab->options()->description() != '' ){
							?>
							<div class="description"><?= $field_tab->options()->description() ?></div>
							<?php
						} ?>
					</div>
				</div>
				<?php
				$index ++;
			}
		?>
	</div>
	<div class="hiweb-fields-form-tabs-content">
		<?php
			$index = 0;
			foreach( $section_data['tabs'] as $tab_global_id => $field_tab ){
				if( array_key_exists( $tab_global_id, $section_data['fields_by_tabs'] ) ){
					?>
					<div data-tab-content="<?= $tab_global_id ?>" <?= $index > 0 ? 'style="display: none"' : '' ?>><?= \hiweb\components\Fields\FieldsFactory_Admin::get_form_section_fields_html( $section_data['fields_by_tabs'][ $tab_global_id ], $field_values ) ?></div>
					<?php
				}
				else{
					?>
					<p><?= __( 'No fields inside tab', 'hiweb-core-4' ) ?></p>
					<?php
				}
				$index ++;
			}
		?>
	</div>
</div>