<?php
	/**
	 * @var Field_Repeat_Row $this
	 */
	
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Col;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Row;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	
	
	$compacted_cols = [];
	$index = 0;
	foreach( $this->get_cols() as $col_id => $col ){
		$compacted_cols[ $index ][] = $col;
		if( !$col->compact() ){
			$index ++;
		}
	}
	$Flex = $this->Field()->get_flex( $this->get_flex_row_id() );
?>
<tr data-row="<?= $this->get_index() ?>" data-flex-id="<?= $this->get_flex_row_id() ?>">
	<td data-drag-handle data-col="_flex_row_id" title="<?= __( 'click - collapse / expand; drag - sorting this row', 'hiweb-core-4' ) ?>">
		<?= FontAwesomeFactory::get( 'fas fa-sort' )->get_style()->get_raw() ?>
		<input type="hidden" name="<?= $this->get_col_input_name( '_flex_row_id' ) ?>"
			   value="<?= $this->get_flex_row_id() ?>"/>
	</td>
	<?php
		if( $this->get_flex_row_id() != '' ){
			echo '<td class="flex-column">
		<table class="hiweb-field-repeat-flex">
			<thead>
			<th class="hiweb-field-repeat-flex-header" colspan="' . count( $compacted_cols ) . '">' . FontAwesomeFactory::get( $Flex->icon() ) . ' ' . $this->Field()->get_flex( $this->get_flex_row_id() )->label() . '</th>
			</thead>
			<tbody>
			<tr>';
		}
		/**
		 * @var int                $index
		 * @var Field_Repeat_Col[] $cols
		 */
		foreach( $compacted_cols as $index => $cols ){
			?>
			<td <?= count( $cols ) > 1 ? 'class="compacted"' : 'data-first-col="' . $cols[0]->ID() . '"' ?>>
				<div class="hiweb-field-type-repeat-col-inner">
					<?php
						/**
						 * @var string           $subindex
						 * @var Field_Repeat_Col $col
						 */
						foreach( $cols as $subindex => $col ){
							?>
							<div data-col="<?= $col->ID() ?>">
								<?php
									if( $this->get_flex_row_id() !== '' ){
										if( $col->label() != '' ){
											?><div class="flex-label"><?= $col->label() ?></div><?php
										}
									}
									else{
										if( $col->Field()->Options()->label() != '' ){
											?><div class="flex-label"><?= $col->Field()->Options()->label() ?></div><?php
										}
									} ?>
								<?= $col->Field()->get_admin_html( $this->get_col_input_value( $col->ID() ), $this->get_col_input_name( $col->ID() ) ) ?>
								<?php if( $col->Field()->Options()->description() != '' ){
									?><p class="description flex-description"><?= $col->Field()->Options()->description() ?></p><?php
								} ?>
							</div>
							<?php
						}
					?>
				</div>
			</td>
			<?php
		}
		if( $this->get_flex_row_id() != '' ){
			echo '</tr></tbody></table></td>';
		}
	?>
	<td data-ctrl-wrap>
		<div>
			<!--<a class="item" title="Копировать строку" data-action-duplicate="<?= $this->get_index() ?>">
				<?= FontAwesomeFactory::get( 'fad fa-copy' )->get_style()->get_raw() ?>
			</a>-->
			<a class="item ctrl-button" title="Удалить строку" data-action-remove="<?= $this->get_index() ?>">
				<?= FontAwesomeFactory::get( 'trash' )->get_style()->get_raw() ?>
			</a>
		</div>
	</td>
</tr>