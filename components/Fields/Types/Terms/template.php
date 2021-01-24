<?php

/**
 * @var Field_Terms $this
 * @var string      $name
 * @var mixed       $value
 */

use hiweb\components\Fields\Types\Terms\Field_Terms;


$terms = $this->get_terms_by_taxonomy();
$attributes = new \hiweb\core\ArrayObject\ArrayObject();
if ($this->options()->placeholder() != '') {
    $attributes->push('placeholder', $this->options()->placeholder());
}
if ($this->options()->multiple()) {
    $attributes->push('multiple', '');
    $attributes->push('size', 1);
    if ($name != '') $name .= '[]';
}
$attributes->push('name', $name);
?>
<div <?= $this->get_admin_wrap_tag_properties([], $name) ?>>
    <select <?= $this->get_admin_input_tags_name_properties($name, '[]') ?> <?= $attributes->get_as_tag_attributes() ?>>
        <?php
        $this->get_html_options_from_terms($value, $terms);
        ?>
    </select>
</div>
