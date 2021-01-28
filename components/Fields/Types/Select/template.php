<?php

/**
 * @var Field_Select $this
 * @var string       $name
 * @var mixed        $value
 */

use hiweb\components\Fields\Types\Select\Field_Select;


$options = $this->options()->options();
$value = $this->get_sanitize_admin_value($value);
if ( !is_array($options)) $options = [];
$options_html = '<option value="">' . htmlentities($this->options()->placeholder()) . '</option>';
foreach ($options as $key => $val) {
    $selected = '';
    if ( !is_null($value) && $key == $value || $val === $value) {
        $selected = 'selected';
    }
    $options_html .= '<option ' . $selected . ' value="' . htmlentities($key == 0 ? $val : $key, ENT_QUOTES, 'UTF-8') . '">' . $val . '</option>';
}
$attributes = new \hiweb\core\ArrayObject\ArrayObject();
$attributes->push('name', $name);
if ($this->options()->multiple()) {
    $attributes->push('multiple', '');
}
if ($this->options()->placeholder() != '') {
    $attributes->push('placeholder', $this->options()->placeholder());
}
?>
<div <?= $this->get_admin_wrap_tag_properties([], $name) ?>>
    <select <?= $this->get_admin_input_tags_name_properties() ?> <?= $attributes->get_as_tag_attributes() ?>><?= $options_html ?></select>
</div>
