<?php

/**
 * @var Field_Select $this
 * @var string       $name
 * @var mixed        $value
 */

use hiweb\components\Fields\Types\Select\Field_Select;


$value = $this->get_sanitize_admin_value($value);
$options = $this->options()->options();
if ( !is_array($options)) $options = [];
///
if($this->options()->allow_empty() != true) $options_html = array_key_exists('', $options) ? '' : '<option value="">' . htmlentities($this->options()->placeholder()) . '</option>';
else $options_html = '';
$optgroup = false;
foreach ($options as $key => $val) {
    if (is_array($val)) {
        if ($optgroup !== false) $options_html .= '</optgroup>';
        $options_html .= '<optgroup label="' . esc_attr($key) . '">';
    } else {
        $val = [ $key => $val ];
    }
    ///
    foreach ($val as $option_key => $option_val) {
        if ($option_key == '' && is_null($this->options()->allow_empty())) $this->options()->allow_empty(true);
        $selected = ( !is_null($value) && $option_key == $value || $option_val === $value) ? 'selected="1"' : '';
        $options_html .= '<option ' . $selected . ' value="' . esc_attr($option_key) . '">' . $option_val . '</option>';
    }
}
if ($optgroup !== false) $options_html .= '</optgroup>';
///
$attributes = get_array();
if ($this->options()->multiple()) {
    $attributes->push('multiple', '');
}
if ($this->options()->placeholder() != '') {
    $attributes->push('placeholder', $this->options()->placeholder());
} else if (array_key_exists('', $options)) {
    $attributes->push('placeholder', $options['']);
}
$attributes->push('data-allow_empty', $this->options()->allow_empty() ? '1' : '0');
$attributes->push('data-max_items', $this->options()->multiple() === true ? 9999 : $this->options()->multiple());
//$attributes->push('data-options', $options);
?>
<div <?= $this->get_admin_wrap_tag_properties([], $name) ?>>
    <select <?= $this->get_admin_input_tags_name_properties($name, absint($this->options()->multiple()) > 0 ? '[]' : null) ?> <?= $attributes->get_as_tag_attributes() ?>><?= $options_html ?></select>
</div>