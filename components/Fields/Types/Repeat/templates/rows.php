<?php
/**
 * @var Field_Repeat $this
 * @var string $name
 */

use hiweb\components\Fields\Types\Repeat\Field_Repeat;


?>
<div data-rows_list="<?= $this->get_unique_id() ?>" data-field-global_id="<?= $this->get_global_id() ?>">
    <?php

    if ($this->value()->have_rows()) {
        foreach ($this->value()->get_rows() as $row) {
            $row->the($name);
        }
    }

    ?>
</div>