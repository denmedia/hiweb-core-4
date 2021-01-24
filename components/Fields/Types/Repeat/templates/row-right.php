<?php
/**
 * @var Field_Repeat_Row $this
 */

use hiweb\components\Fields\Types\Repeat\Field_Repeat_Row;


?>
<div class="repeat__row__right">
    <a class="item ctrl-button" title="Дублировать строку" data-unique_id="<?= $this->field()->get_unique_id() ?>" data-action="row-control">
        <?= get_fontawesome('fas fa-ellipsis-h') ?>
    </a>
</div>