<?php
	
	/**
	 * @var Field_Repeat $this
	 * @var string       $name
	 */
	
	use hiweb\components\Fields\FieldsAdminFactory;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat;
	
	
	$Field = FieldsAdminFactory::get_the_field();
	
	if( !$Field instanceof Field_Repeat ) return;
	$name = FieldsAdminFactory::get_the_field_name();
	$Value = $Field->Value();
	$rand_field_id = 'hiweb_field_repeat_' . \hiweb\core\Strings::rand( 5 );

?>
<div class="hiweb-field-type-repeat" data-input-name="<?= $name ?>"
	 data-global-id="<?= $Field->global_ID() ?>"
	 data-id="<?= $Field->ID() ?>"
	 data-flex="<?= $Field->have_flex_cols() ? '1' : '0' ?>"
	 data-rand-id="<?= $this->get_rand_id() ?>">
	<?php if( !$Field->Options()->have_cols() ){
		?>
		<p class="empty-message"><?= sprintf( __( 'For repeat input [%s] not add col fields. For that do this: <code>$field->add_col_field( add_field_text(...) )</code>' ), $Field->ID() ) ?></p><?php
	}
	else{
		?>
		<table class="hiweb-field-type-repeat-table"><?php
		echo $Field->get_head_html( true );
		?>
		<tbody data-rows-list>
		<?php
			if( $Value->have_rows() ){
				foreach( $Value->get_rows() as $row_index => $row ){
					$row->the($name);
				}
			}
		?>
		</tbody>
		<tbody data-rows-message>
		<tr data-row-empty="<?= $Value->have_rows() ? '1' : '0' ?>">
			<td colspan="<?= $Field->have_flex_cols() ? 3 : ( count( $this->Options()->get_cols() ) + 2 ) ?>"><p class="message"><?= 'Таблица пуста. Для добавления хотя бы одног поля, кликните по кнопке "+"' ?></p>
			</td>
		</tr>
		</tbody>
		
		<?php
		echo $this->get_head_html( false );
		?></table><?php
	} ?>
</div>