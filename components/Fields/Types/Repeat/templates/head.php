<?php
	
	use hiweb\components\Fields\Types\Repeat\Field_Repeat;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Col;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Flex;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Value;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	
	
	/** @var bool $thead */
	/** @var string $handle_title */
	/** @var Field_Repeat $this */
	
	echo $thead ? '<thead class="ui segment">' : '<tfoot class="ui segment">' ?>
	<tr>
		<th class="collapsing">
			<!--<a href="#" title="<?= __( 'collapse / expand all rows', 'hiweb-core-4' ) ?>"><?= FontAwesomeFactory::get( 'far fa-compress-alt' ) ?></a>-->
		</th>
		<?php
			if( $this->have_flex_cols() ){
				?>
				<th class="flex-column"><?= $handle_title ?></th><?php
			}
			else{
				if( $this->Options()->have_cols() ){
					///COMPACT GROUP
					$compacted_cols = [];
					$index = 0;
					foreach( $this->Options()->get_cols()[''] as $col_id => $col ){
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
						<th <?= count( $cols ) > 1 ? '' : 'data-col="' . $cols[0]->Field()->ID() . '"' ?>
								style="width:<?= $width ?>" class="<?= count( $cols ) > 1 ? 'compacted' : '' ?>">
							<?= $cols[0]->label() . ( $cols[0]->description() != '' ? '<p class="description">' . $cols[0]->description() . '</p>' : '' ) ?>
						</th>
						<?php
					}
				}
				
				?>
				
				<?php
			} ?>
		<th data-ctrl-wrap>
			<?php
				if( $this->have_flex_cols() ){
					$rand_id = \hiweb\core\Strings::rand();
					?>
					<a href="" class="ctrl-button" data-action-open-flex-submenu="<?= $rand_id ?>">
						<?= FontAwesomeFactory::get( 'fas fa-ellipsis-v' )->get_style()->get_raw() ?>
						<div style="display: none" id="<?= $rand_id ?>">
							<div class="hiweb-fields-dropdown-menu">
								<?php
									/**
									 * @var string            $flex_id
									 * @var Field_Repeat_Flex $flex
									 */
									foreach( $this->get_flexes() as $flex_id => $flex ){
										?>
										<a href="" class="dropdown-item" data-action-add="<?= $thead ? '1' : '0' ?>" data-flex-id="<?= $flex_id ?>" data-field-global-id="<?=$this->global_ID()?>"><?= FontAwesomeFactory::get( $flex->icon() ) ?> <?= $flex->label() ?></a>
										<?php
									}
								?>
							</div>
						</div>
					</a>
					<?php
				}
				else{
					?>
					<a href="" data-action-add="<?= $thead ? '1' : '0' ?>">
						<?= FontAwesomeFactory::get( 'fas fa-plus-circle' )->get_style()->get_raw() ?>
					</a>
					<?php
				}
			?>
			<!--<button title="Clear all table rows..." class="dashicons dashicons-marker" data-action-clear=""></button>-->
		</th>
	</tr>
<?= $thead ? '</thead>' : '</tfoot>';