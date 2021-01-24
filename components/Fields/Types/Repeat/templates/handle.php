<?php
/**
 * @var Field_Repeat $this
 * @var bool         $handle
 */

use hiweb\components\Fields\Types\Repeat\Field_Repeat;


if ( !$this->have_cols()) return;
?>
    <div class="repeat__handle">
        <div class="repeat__handle__left">
            <a href="#" data-action="collapse_all"><?= get_fontawesome('far fa-compress-alt') ?></a>
            <a href="#" data-action="expand_all"><?= get_fontawesome('far fa-expand-alt') ?></a>
        </div>
        <div class="repeat__handle__cols">

        </div>
        <div class="repeat__handle__right"><a href="#" data-unique_id="<?= $this->get_unique_id() ?>" data-action="dropdown" data-index="<?= $handle ? '-1' : '+1' ?>"><?= (string)get_fontawesome('fas fa-ellipsis-v') ?></a></div>
    </div>
<?php $handle = false;