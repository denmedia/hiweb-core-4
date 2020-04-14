<?php

	namespace hiweb\components\Fields\Types\Repeat;


	class Field_Repeat_Row{

		/** @var input */
		public $parent_input;

		public $index = 0;
		/** @var col[] */
		public $cols = [];
		public $flex_row_id = '';

		public $have_cols = false;

		public $data = [];


		public function __construct( input $parent_input, $data = [], $row_index = 0 ){
			$this->parent_input = $parent_input;
			$this->flex_row_id = array_key_exists( '_flex_row_id', $data ) ? $data['_flex_row_id'] : '';
			$this->data = $data;
			foreach( $parent_input->get_cols( $this->flex_row_id ) as $id => $col ){
				$this->have_cols = true;
				$this->cols[ $id ] = clone $col;
				$this->cols[ $id ]->set_row( $this );
				$this->cols[ $id ]->input()->name( $this->parent_input->name() . '[' . $row_index . '][' . $id . ']' );
				if( isset( $data[ $id ] ) ){
					$this->cols[ $id ]->value()->set( $data[ $id ] );
				}
			}
		}


		public function the(){
			if( !$this->have_cols ){
				return;
			}
			$compacted_cols = [];
			$index = 0;
			foreach( $this->cols as $col_id => $col ){
				$compacted_cols[ $index ][] = $col;
				if( !$col->compact() ){
					$index ++;
				}
			}
			?>
			<tr data-row="<?= $this->index ?>" data-flex-id="<?= $this->flex_row_id ?>" class="ui segment">
				<td data-drag data-col="_flex_row_id">
					<i class="sort icon"></i>
					<input type="hidden" name="<?= $this->parent_input->name() ?>[<?= $this->index ?>][_flex_row_id]"
					       value="<?= $this->flex_row_id ?>"/>
				</td>
				<?php

					if( $this->parent_input->have_flex_rows() ){
						?>
						<td class="flex-column">
							<table class="hiweb-field-repeat-flex">
								<thead>
								<?php
									/**
									 * @var int   $index
									 * @var col[] $cols
									 */
									foreach( $compacted_cols as $index => $cols ){
										?>
										<th class="hiweb-field-repeat-flex-header"><?= $cols[0]->label() ?></th>
										<?php
									} ?>
								</thead>
								<tbody>
								<tr>
									<?php
										foreach( $compacted_cols as $index => $cols ){
											?>
											<td <?= count( $cols ) > 1 ? 'class="compacted"' : 'data-first-col="' . $cols[0]->id() . '"' ?>>
												<?php
													foreach( $cols as $subindex => $col ){
														?>
														<div class="compacted-col-input" data-col="<?= $col->id() ?>">
															<?php if( $subindex > 0 ){
																?><p class="flex-label"><?= $col->label() ?></p><?php
															} ?>
															<?php $col->the() ?>
															<?php if( $col->description() != '' ){
																?>
																<p class="description flex-description"><?= $col->description() ?></p><?php
															} ?>
														</div>
														<?php
													}
												?>
											</td>
											<?php
										} ?>
								</tr>
								</tbody>
							</table>
						</td>
						<?php
					} else {
						//							$last_compact = false;
						//							foreach ( $this->cols as $col ) {
						//								?>
						<!--								<td data-col="--><? //= $col->id() ?><!--" class="--><? //= ( $col->compact() || $last_compact ) ? 'compact' : '' ?><!--">--><?php //$col->the(); ?><!--</td>-->
						<!--								--><?php
						//								$last_compact = $col->compact();
						//							}
						foreach( $compacted_cols as $index => $cols ){
							?>
							<td <?= count( $cols ) > 1 ? 'class="compacted"' : 'data-first-col="' . $cols[0]->id() . '"' ?>>
								<?php
									foreach( $cols as $subindex => $col ){
										?>
										<div class="compacted-col-input" data-col="<?= $col->id() ?>">
											<?php if( $subindex > 0 ){
												?><p class="flex-label"><?= $col->label() ?></p><?php
											} ?>
											<?php $col->the() ?>
											<?php if( $col->description() != '' ){
												?><p class="description flex-description"><?= $col->description() ?></p><?php
											} ?>
										</div>
										<?php
									}
								?>
							</td>
							<?php
						}
					} ?>
				<td data-ctrl>
					<div class="ui vertical mini compact icon menu">
						<a class="item" title="Копировать строку" data-action-duplicate="<?= $this->index ?>">
							<i class="copy icon"></i>
						</a>
						<a class="item" title="Удалить строку" data-action-remove="<?= $this->index ?>">
							<i class="trash icon"></i>
						</a>
					</div>
					<!--<button title="Duplicate row" class="dashicons dashicons-admin-page" data-action-duplicate="<?= $this->index ?>"></button>
						<button title="Remove row..." class="dashicons dashicons-trash" data-action-remove="<?= $this->index ?>"></button>-->
				</td>
			</tr>
			<?php

		}

	}