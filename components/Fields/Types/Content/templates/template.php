<?php
/**
 * @var Field_Content $this
 * @var string|null   $value
 * @var string|null   $name
 */

use hiweb\components\Fields\Types\Content\Field_Content;


$rand_id = 'hiweb-field_text-' . \hiweb\core\Strings::rand(5);
?>
<div <?= $this->get_admin_wrap_tag_properties([ 'data-rand_id' => $rand_id, 'class' => [ ($value == '' ? 'empty' : '') ] ]) ?>>
    <textarea style="display: none;" <?= $this->get_admin_input_tags_name_properties($name) ?> id="<?= $rand_id ?>"><?= $value ?></textarea>
    <div data-editor_wrap="<?= $rand_id ?>"><?= $value ?></div>
    <div class="message-empty"><span class="icon"><?= get_fontawesome('far fa-text-size') ?></span> <?= __('Click to edit', 'hiweb-core-4') ?></div>
    <div class="clear-both"></div>
</div>