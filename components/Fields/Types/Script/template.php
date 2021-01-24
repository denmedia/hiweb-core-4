<?php
/**
 * @var Field  $this
 * @var string $name
 * @var mixed  $value
 */

use hiweb\components\Fields\Field;


$rand_id = 'hiweb_feild_script_' . \hiweb\core\Strings::rand(5);
?>
<div <?= $this->get_admin_wrap_tag_properties([ 'data-rand_id' => $rand_id, $name ]) ?>>
    <textarea <?= $this->get_admin_input_tags_name_properties($name) ?>><?= htmlentities($value) ?></textarea>
</div>