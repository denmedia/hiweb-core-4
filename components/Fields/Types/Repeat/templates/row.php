<?php
/**
 * @var Field_Repeat_Row $this
 */

use hiweb\components\Fields\Types\Repeat\Field_Repeat_Row;


?>
<div class="repeat__row<?= $this->get_flex_row_collapsed() ? ' repeat__row__collapsed' : '' ?>" data-row="<?= $this->get_index() ?>" data-unique_id="<?= $this->field()->get_unique_id() ?>">
    <div class="repeat__row_inner">
        <?php include __DIR__ . '/row-left.php' ?>
        <?php include __DIR__ . '/row-fields.php' ?>
        <?php include __DIR__ . '/row-right.php' ?>
    </div>
</div>