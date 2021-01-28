<?php
/**
 * @var Field_Separator $this
 */

use hiweb\components\Fields\Types\Separator\Field_Separator;


?>
<div <?= $this->get_admin_wrap_tag_properties() ?>>
    <?php
    if ($this->options()->separator_label() != '') {
    ?>
    <<?= $this->options()->tag_label() ?> class="hw-field-separator-title<?= $this->options()->separator_description() != '' ? ' has-description' : ' no-description' ?>"><?= $this->options()->separator_label() ?></<?= $this->options()->tag_label() ?>>
<?php
}
?>
<?php if ($this->options()->separator_description() != '') {
    ?>
    <<?= $this->options()->tag_description() ?>  class="hw-field-separator-description"><?= $this->options()->separator_description() ?></<?= $this->options()->tag_description() ?>>
    <?php
} ?>
</div>