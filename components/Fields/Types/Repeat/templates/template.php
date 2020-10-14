<?php
	
	/**
	 * @var Field_Repeat $this
	 * @var string       $name
	 */
	
	use hiweb\components\Fields\Types\Repeat\Field_Repeat;
	use hiweb\core\Strings;
	
	
	$this->unique_id = 'hiweb_field_repeat_' . $this->id() . '_' . Strings::rand( 12 );
	
	$Value = $this->Value();
	$rand_field_id = 'hiweb_field_repeat_' . \hiweb\core\Strings::rand( 5 );
	
	$root_attributes = new \hiweb\core\ArrayObject\ArrayObject( [
		'class' => 'hiweb-field-type-repeat',
		'data-input_name' => $name,
		'data-id' => $this->id(),
		'data-global_id' => $this->global_ID(),
		'data-have_flex' => $this->have_flex_cols(),
		'data-unique_id' => $this->get_unique_id(),
		'data-text_confirm_clear_all' => $this->options()->text_confirm_clear_all()
	] );

?>
<div <?= $root_attributes->get_as_tag_attributes() ?>>
	<?php if( !$this->options()->have_cols() ){
		?>
		<p class="empty-message"><?= sprintf( __( 'For repeat input [%s] not add col fields. For that do this: <code>$this->add_col_field( add_field_text(...) )</code>' ), $this->id() ) ?></p><?php
	}
	else{
		?>
		<table class="hiweb-field-type-repeat-table"><?php
		echo $this->get_head_html( true );
		?>
		<tbody data-rows_list="<?= $this->global_ID() ?>">
		<?php
			if( $Value->have_rows() ){
				foreach( $Value->get_rows() as $row_index => $row ){
					$row->the( $name );
				}
			}
		?>
		</tbody>
		<tbody data-rows_message>
		<tr data-row_empty="<?= $Value->have_rows() ? '1' : '0' ?>">
			<td colspan="<?= $this->have_flex_cols() ? 3 : ( count( $this->options()->get_cols() ) + 2 ) ?>"><p class="message"><?= __( 'The table is empty. To add at least one field, click on the "+" button', 'hiweb-core-4' ) ?></p>
			</td>
		</tr>
		</tbody>
		
		<?php
		echo $this->get_head_html( false );
		?></table><?php
	} ?>
</div>