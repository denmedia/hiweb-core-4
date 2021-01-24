<?php
/**
 * @var Field_Repeat $this
 */

use hiweb\components\Fields\Types\Repeat\Field_Repeat;
use hiweb\components\Fields\Types\Repeat\Field_Repeat_Flex;


?>
<div style="display: none">
    <div class="hiweb-fields-dropdown-menu" data-dropdown-content="<?= $this->get_unique_id() ?>">
        <?php
        /**
         * @var string            $flex_id
         * @var Field_Repeat_Flex $flex
         */
        foreach ($this->get_flexes() as $flex_id => $flex) {
            ?>
            <a href="#" class="dropdown-item" data-action="add" data-flex_id="<?= $flex_id ?>" data-unique_id="<?= $this->get_unique_id() ?>" data-global_id="<?= $this->global_id() ?>"><?= get_fontawesome($flex->icon()) ?> <?= $flex->label() ?></a>
            <?php
        }
        ?>
        <div class="separator"></div>
        <!--<a href="#" class="dropdown-item" data-action_copy data-unique_id="<?= $this->get_unique_id() ?>"><?= get_fontawesome('fad fa-copy') ?> <?= $this->options()->label_button_copy_all() ?></a>-->
        <a class="dropdown-item" title="<?= __('Copy all rows', 'hiweb-core-4') ?>" data-unique_id="<?= $this->get_unique_id() ?>" data-action="copy"><?= get_fontawesome('fad fa-copy') ?> <?= __('Copy', 'hiweb-core-4') ?></a>
        <a class="dropdown-item <?= \hiweb\components\Fields\Types\Repeat\Field_Repeat::is_buffer_exists() ? '' : 'disabled' ?>" title="<?= __('Paste rows', 'hiweb-core-4') ?>" data-unique_id="<?= $this->get_unique_id() ?>" data-action="paste"><?= get_fontawesome('fad fa-paste') ?> <?= __('Paste', 'hiweb-core-4') ?></a>
        <div class="separator"></div>
        <a href="#" class="dropdown-item" data-action="collapse_all" data-unique_id="<?= $this->get_unique_id() ?>"><?= get_fontawesome('fad fa-compress-alt') ?> <?= $this->options()->label_button_collapse_all() ?></a>
        <a href="#" class="dropdown-item" data-action="clear" data-unique_id="<?= $this->get_unique_id() ?>"><?= get_fontawesome('fad fa-trash-alt') ?> <?= $this->options()->label_button_clear_all() ?></a>
    </div>
    <div class="hiweb-field_repeat__row-control" data-row-control="<?= $this->get_unique_id() ?>">
        <div>
            <a class="" title="Удалить строку" data-unique_id="<?= $this->get_unique_id() ?>" data-action="remove">
                <span class="icon"><?= get_fontawesome('trash') ?></span>
                <span class="label"><?= __('Remove', 'hiweb-core-4') ?></span>
            </a>
            <span class="separator"></span>
            <a class="" title="<?= __('Collapse / Expand Row', 'hiweb-core-4') ?>" data-unique_id="<?= $this->get_unique_id() ?>" data-action="collapse">
                <span class="icon"><?= get_fontawesome('fad fa-compress-alt') ?></span>
                <span class="label"><?= __('Collapse', 'hiweb-core-4') ?></span>
            </a>
            <span class="separator"></span>
            <a href="#" data-unique_id="<?= $this->get_unique_id() ?>" data-action="dropdown">
                <span class="icon"><?= (string)get_fontawesome('fas fa-ellipsis-v') ?></span>
                <span class="label"><?= __('Append row', 'hiweb-core-4') ?></span>
            </a>
        </div>
        <div>
            <a class="" title="<?= __('Copy row', 'hiweb-core-4') ?>" data-unique_id="<?= $this->get_unique_id() ?>" data-action="copy">
                <span class="icon"><?= get_fontawesome('fad fa-copy') ?></span>
                <span class="label"><?= __('Copy', 'hiweb-core-4') ?></span>
            </a>
            <a class="<?= \hiweb\components\Fields\Types\Repeat\Field_Repeat::is_buffer_exists() ? '' : 'disabled' ?>" title="<?= __('Paste row', 'hiweb-core-4') ?>" data-unique_id="<?= $this->get_unique_id() ?>" data-action="paste">
                <span class="icon"><?= get_fontawesome('fad fa-paste') ?></span>
                <span class="label"><?= __('Paste', 'hiweb-core-4') ?></span>
            </a>
            <a class="" title="<?= __('Duplicate row', 'hiweb-core-4') ?>" data-unique_id="<?= $this->get_unique_id() ?>" data-action="duplicate">
                <span class="icon"><?= get_fontawesome('fad fa-clone') ?></span>
                <span class="label"><?= __('Duplicate', 'hiweb-core-4') ?></span>
            </a>
        </div>
    </div>
</div>