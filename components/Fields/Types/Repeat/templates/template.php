<?php
/**
 * @var Field_Repeat $this
 * @var string       $name
 */

use hiweb\components\Fields\Types\Repeat\Field_Repeat;

if ( !$this->have_cols()) {
    ?>
    <div class="hiweb-field_repeat">
        <div class="repeat__messages">
            <div data-message="cols-empty">
                <div class="icon"><?= get_fontawesome('fad fa-file-code') ?></div>
                <?= sprintf(__('For repeat input [%s] not add col fields. For that do this: <code>add_field_repeat(...)->add_col_field( add_field_text(...) )</code>'), $this->id()) ?>
            </div>
        </div>
    </div>
    <?php
} else {
    $this->get_unique_id('repeat-field-' . $this->get_global_id() . '-' . \hiweb\core\Strings::rand(5));
    $handle = true;
    ?>
    <div <?= $this->get_admin_wrap_tag_properties([ 'data-unique_id' => $this->get_unique_id() ], $name) ?> data-status="loaded" data-collpase-status="expanded" data-rows-count="<?= count($this->value()->get_rows()) ?>">
        <?php include __DIR__ . '/handle.php' ?>
        <?php include __DIR__ . '/rows.php' ?>
        <?php include __DIR__ . '/message-empty.php' ?>
        <?php include __DIR__ . '/handle.php' ?>
    </div>
    <?php include __DIR__ . '/dropdown.php';
}