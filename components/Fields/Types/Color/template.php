<?php

use hiweb\components\Fields\Types\Color\Field_Color;


/**
 * @var Field_Color $this
 * @var string      $name
 */
?>
<div <?= $this->get_admin_wrap_tag_properties() ?>><input type="text" <?= $this->get_admin_input_tags_name_properties($name) ?> value="<?= htmlentities($this->get_sanitize_admin_value($value)) ?>"/></div>