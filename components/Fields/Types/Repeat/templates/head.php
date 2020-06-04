<?php
	
	use hiweb\components\Fields\Types\Repeat\Field_Repeat;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Col;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Flex;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Value;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	
	
	/** @var bool $thead */
	/** @var string $handle_title */
	/** @var Field_Repeat $this */
	
	echo $thead ? '<thead>' : '<tfoot>' ?>
	<tr>
		<th>
			<!--<a href="#" title="<?= __( 'collapse / expand all rows', 'hiweb-core-4' ) ?>"><?= FontAwesomeFactory::get( 'far fa-compress-alt' ) ?></a>-->
		</th>
		<?php
			if( $this->have_flex_cols() ){
				?>
				<th class="flex-column"><?= $handle_title ?></th><?php
			}
			else{
				if( $this->options()->have_cols() ){
					///COMPACT GROUP
					$compacted_cols = [];
					$index = 0;
					foreach( $this->options()->get_cols()[''] as $col_id => $col ){
						$compacted_cols[ $index ][] = $col;
						if( !$col->compact() ){
							$index ++;
						}
					}
					///
					$width_cols = [];
					foreach( $compacted_cols as $index => $cols ){
						/** @var Field_Repeat_Col $col */
						foreach( $cols as $subindex => $col ){
							if( !isset( $width_cols[ $index ] ) || ( isset( $width_cols[ $index ] ) && $col->width() > $width_cols[ $index ] ) ){
								$width_cols[ $index ] = $col->width();
							}
						}
					}
					///
					/** @var Field_Repeat_Col[] $cols */
					foreach( $compacted_cols as $index => $cols ){
						$width = ceil( $width_cols[ $index ] / array_sum( $width_cols ) * 100 ) . '%';
						?>
						<th <?= count( $cols ) > 1 ? '' : 'data-col="' . $cols[0]->field()->id() . '"' ?>
								style="width:<?= $width ?>" class="<?= count( $cols ) > 1 ? 'compacted' : '' ?>">
							<?php
								if(count($cols) == 1) {
									echo $cols[0]->label() . ( $cols[0]->description() != '' ? '<p class="description">' . $cols[0]->description() . '</p>' : '' );
								}
							?>
						</th>
						<?php
					}
				}
				
				?>
				
				<?php
			} ?>
		<th data-ctrl_wrap="<?= $this->get_unique_id() ?>">
			<div class="ctrl-button" data-dropdown="<?= $this->get_unique_id() ?>">
				<?= FontAwesomeFactory::get( 'fas fa-ellipsis-v' )->get_style()->get_raw() ?>
				<div style="display: none" data-unique_id="<?= $this->get_unique_id() ?>">
					<div class="hiweb-fields-dropdown-menu" data-unique_id="<?= $this->get_unique_id() ?>">
						<?php
							/**
							 * @var string            $flex_id
							 * @var Field_Repeat_Flex $flex
							 */
							foreach( $this->get_flexes() as $flex_id => $flex ){
								?>
								<a href="#" class="dropdown-item" data-action_add="<?= $thead ? '1' : '0' ?>" data-flex_id="<?= $flex_id ?>" data-unique_id="<?= $this->get_unique_id() ?>" data-globa_id="<?= $this->global_ID() ?>"><?= FontAwesomeFactory::get( $flex->icon() ) ?> <?= $flex->label() ?></a>
								<?php
							}
						?>
						<div class="separator"></div>
						<!--<a href="#" class="dropdown-item" data-action_copy data-unique_id="<?= $this->get_unique_id() ?>"><?= FontAwesomeFactory::get( '<i class="fad fa-copy"></i>' ) ?> <?= $this->options()->label_button_copy_all() ?></a>-->
						<a href="#" class="dropdown-item" data-action_clear data-unique_id="<?= $this->get_unique_id() ?>"><?= FontAwesomeFactory::get( '<i class="fad fa-trash-alt"></i>' ) ?> <?= $this->options()->label_button_clear_all() ?></a>
					</div>
				</div>
			</div>
		</th>
	</tr>
<?= $thead ? '</thead>' : '</tfoot>';