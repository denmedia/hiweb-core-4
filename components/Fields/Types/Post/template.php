<?php

/**
 * @var Field_Post $this
 * @var array      $selected
 * @var string     $name
 * @var mixed      $value
 */

use hiweb\components\Fields\Types\Post\Field_Post;


$attributes = new \hiweb\core\ArrayObject\ArrayObject();

///
$attributes->push('data-global-id', $this->global_id());
if ($this->options()->multiple()) {
    $attributes->push('multiple', '');
    $attributes->push('size', 1);
    if ($name != '') $name .= '[]';
}
$attributes->push('placeholder', $this->options()->placeholder());
//$attributes->push('name', $name);
$attributes->push('data-options', [ 'post_type' => $this->options()->post_type() ]);
$attributes->push('data-value', (array)$value);
?>
<div <?= $this->get_admin_wrap_tag_properties($attributes) ?>>
    <select <?= $this->get_admin_input_tags_name_properties($name) ?> <?= $attributes->get_as_tag_attributes() ?>>
        <?php
        foreach ($selected as $val => $title) {
            ?>
            <option value="<?= $val ?>" selected><?= $title ?></option>
            <?php
        }
        ?>
    </select>
</div>
