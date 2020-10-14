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
	$row_attributes = new \hiweb\core\ArrayObject\ArrayObject( [
		'data-row' => $this->get_index(),
		'data-flex_id' => $this->get_flex_row_id(),
		'data-unique_id' => $this->Field->get_unique_id()
	] );
?>
<tr <?= $row_attributes->get_as_tag_attributes() ?>>
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
			<th class="hiweb-field-repeat-flex-header" colspan="' . count( $compacted_cols ) . '">' . FontAwesomeFactory::get( $Flex->icon() ) . ' ' . $Flex->label() . ' ' . ( $Flex->description() == '' ? '' : '<div class="flex-description">' . $Flex->description() . '</div>' ) . '</th>
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
											?>
											<div class="flex-label"><?= $col->label() ?>:</div><?php
										}
									}
									else{
										if( count( $cols ) == 1 ){
											//do nothing
										}
										elseif( $col->label() != '' ){
											?>
											<div class="flex-label"><?= $col->label() ?>:</div><?php
										}
										elseif( $col->field()->options()->label() != '' ){
											?>
											<div class="flex-label"><?= $col->field()->options()->label() ?>:</div><?php
										}
									} ?>
								<?php
									if( $col->field() instanceof \hiweb\components\Fields\Field ){
										ob_start();
										try{
											echo $col->field()->get_admin_html( $this->get_col_input_value( $col->ID() ), $this->get_col_input_name( $col->ID() ) );
										} catch( Exception $e ){
											echo 'error...';
											\hiweb\components\Console\ConsoleFactory::add( 'Error white print admin html for col', 'warn', __FILE__, $col->field(), true );
										}
										echo ob_get_clean();
									}
									else{
										\hiweb\components\Console\ConsoleFactory::add( 'not the Filed instance', 'warn', __FILE__, $col, true );
									}
								?>
								<?php if( $col->description() != '' ){
									?><p class="description flex-description"><?= $col->description() ?></p><?php
								}
								elseif( $col->field()->options()->description() != '' ){
									?><p class="description flex-description"><?= $col->field()->options()->description() ?></p><?php
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
	<td data-ctrl_wrap>
		<div>
			<!--<a class="item" title="Копировать строку" data-action-duplicate="<?= $this->get_index() ?>">
				<?= FontAwesomeFactory::get( 'fad fa-copy' )->get_style()->get_raw() ?>
			</a>-->
			<a class="item ctrl-button" title="Удалить строку" data-unique_id="<?= $this->Field()->get_unique_id() ?>" data-action-remove="<?= $this->get_index() ?>">
				<?= FontAwesomeFactory::get( 'trash' )->get_style()->get_raw() ?>
			</a>
		</div>
	</td>
</tr>