<?php

use hiweb\components\Fields\Types\Textarea\Field_Textarea;


/**
 * @var Field_Textarea $this
 * @var string     $name
 * @var mixed      $value
 */

$attributes = \hiweb\core\ArrayObject\ArrayObject::get_instance([]);
if (intval($this->options()->rows()) > 0) {
    $attributes->push('rows', $this->options()->rows());
}
if ($this->options()->placeholder() != '') {
    $attributes->push('placeholder', $this->options()->placeholder());
}
?>
<div <?= $this->get_admin_wrap_tag_properties() ?>>
    <textarea <?= $this->get_admin_input_tags_name_properties($name) ?> <?=$attributes->get_as_tag_attributes()?>><?= $value ?></textarea>
</div>