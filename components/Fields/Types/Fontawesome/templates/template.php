<?php

use hiweb\components\Fields\Field;
use hiweb\components\FontAwesome\FontAwesomeFactory;


/**
 * @var       $this Field
 * @var       $name string
 * @var mixed $value
 */

$FontAwesome = FontAwesomeFactory::get($value);
$FontAwesome_unknown = FontAwesomeFactory::get('question');
$FontAwesome_loader = FontAwesomeFactory::get('fas fa-spinner-third');
$rand_id = 'hiweb-field-fontawesome-' . \hiweb\core\Strings::rand(5);
?>
<div <?= $this->get_admin_wrap_tag_properties([ 'data-input_empty' => ($value !== '' ? '0' : '1'), 'data-selected' => $FontAwesome->is_exists() ? '1' : '0', 'data-status' => 'loaded', 'data-rand_id' => $rand_id ]) ?>>
    <div class="button-group">
        <div data-icon-preview><?= $FontAwesome->is_exists() ? $FontAwesome : $FontAwesome_unknown ?></div>
        <input type="text" <?= $this->get_admin_input_tags_name_properties($name) ?> value="<?= htmlentities($value) ?>"/>
        <button data-click="clear" class="button button-link-delete"><?= get_fontawesome('fad fa-times-circle') ?></button>
        <button data-click="find" class="button button"><?= get_fontawesome('fas fa-search') ?></button>
        <button data-click="styles" class="button"><?= get_fontawesome('fad fa-palette') ?></button>
        <button data-click="all" class="button button-primary"><?= get_fontawesome('fad fa-icons') ?></button>
    </div>
    <div style="display: none">
<!--        <div class="hiweb-field_fontawesome__dropdown__wrap">-->
<!--            <div class="hiweb-field_fontawesome__dropdown">-->
<!--                dropdown-->
<!--            </div>-->
<!--        </div>-->
        <div data-fontawesome-icon-unknown><?= $FontAwesome_unknown ?></div>
        <div data-fontawesome-icon-loader><span class="loader-rotate"><?= $FontAwesome_loader ?></span></div>
        <div data-fontawesome-result-place="<?= $rand_id ?>">result</div>
        <div data-fontawesome-styles-place="<?= $rand_id ?>">styles</div>-->
    </div>
</div>