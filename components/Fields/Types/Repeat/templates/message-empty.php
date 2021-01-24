<?php
/**
 * @var Field_Repeat $this
 */

use hiweb\components\Fields\Types\Repeat\Field_Repeat;


?>
<div class="repeat__message_empty" data-unique_id="<?= $this->get_unique_id() ?>">
    <div class="repeat__message_empty_inner">
        <div class="icon"><?= get_fontawesome('fad fa-layer-plus') ?></div>
        <?= sprintf(__('The table is empty. To add at least one field, click on the <b>%s</b> button', 'hiweb-core-4'), (string)get_fontawesome('fas fa-ellipsis-v')) ?>
    </div>
</div>