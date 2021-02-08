<?php
/**
 * @var Field_Repeat_Row $this
 */

use hiweb\components\Fields\Types\Repeat\Field_Repeat_Row;


?>
<div class="repeat__row__left" data-drag-handle="<?= $this->field()->get_unique_id() ?>" data-action="collapse" data-unique_id="<?= $this->field()->get_unique_id() ?>" title="<?= __('click - collapse / expand; drag - sorting this row', 'hiweb-core-4') ?>">
    <?= get_fontawesome('fas fa-sort') ?>
    <div data-field-id="_flex_row_id" data-field-input_name="" data-unique_id="<?= $this->field()->get_unique_id() ?>">
        <input type="hidden" name="<?= $this->get_col_input_name('_flex_row_id') ?>" value="<?= $this->get_flex_row_id() ?>"/>
    </div>
    <div data-field-id="_flex_row_collapsed" data-field-input_name="" data-unique_id="<?= $this->field()->get_unique_id() ?>">
        <input data-flex-row-collapsed-input="<?= $this->field()->get_unique_id() ?>" type="hidden" name="<?= $this->get_col_input_name('_flex_row_collapsed') ?>" value="<?= $this->get_flex_row_collapsed() ? '1' : '0' ?>"/>
    </div>
</div>