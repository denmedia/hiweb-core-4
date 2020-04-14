<?php

	/**
	 * @var Field_Repeat $this
	 */

	use hiweb\components\Fields\FieldsAdminFactory;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat;


	$Field = FieldsAdminFactory::get_the_field();

	if( !$Field instanceof Field_Repeat ) return;
	$name = FieldsAdminFactory::get_the_field_name();
	$Field_Repeat_Value = $Field->Value( FieldsAdminFactory::get_the_field_value() );

?>
<div class="hiweb-field-type-repeat" data-input-name="<?= $name ?>"
	 data-global-id="<?= $Field->global_ID() ?>"
	 data-id="<?= $Field->ID() ?>"
	 data-flex="<?= $Field_Repeat_Value->have_flex_rows() ? '1' : '0' ?>">

	<p v-if="data.cols.length == 0" class="empty-message">{{ data }}<?= sprintf( __( 'For repeat input [%s] not add col fields. For that do this: <code>$field->add_col_field( add_field_text(...) )</code>', 'hiweb-core-4' ), $this->ID() ) ?></p>
	<div v-else class="rows-list">
		{{ message }}
	</div>
</div>
<script>
    if (typeof hiweb_field_type_repeat_value === 'undefined') {
        hiweb_field_type_repeat_value = {};
    }
    hiweb_field_type_repeat_value['<?=$name?>'] = <?=json_encode( [ 'value' => FieldsAdminFactory::get_the_field_value(), 'cols' => $Field->Options()->get_cols_simple() ] )?>;
</script>