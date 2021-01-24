<?php
/**
 * @var Field_Repeat_Row $this
 */

use hiweb\components\Fields\Types\Repeat\Field_Repeat_Col;
use hiweb\components\Fields\Types\Repeat\Field_Repeat_Row;


$fields_by_cols = [];
$fields_hidden = [];
$widths_by_cols = [];
$index = 0;
foreach ($this->get_cols() as $col_id => $col) {
    //skip hidden fields
    if ($col->hidden()) {
        $fields_hidden[$col_id] = $col;
        continue;
    }
    ///collect field
    $fields_by_cols[$index][$col_id] = $col;
    ///set width
    if ( !array_key_exists($index, $widths_by_cols)) {
        $widths_by_cols[$index] = $col->width();
    } elseif ($col->width() > $widths_by_cols[$index]) {
        $widths_by_cols[$index] = $col->width();
    }
    ///count index
    if ( !$col->compact()) {
        $index ++;
    }
}
$flex = $this->field()->get_flex($this->get_flex_row_id());

?>
    <div class="repeat__row__fields">
        <?php

        if ($flex->id() != '') {
            ?>
            <div class="repeat__row__flex_label_wrap">
                <div class="repeat__row__flex_label">
                    <?= $flex->icon() != '' ? get_fontawesome($flex->icon()) . ' ' : '' ?><?= $flex->label() ?>
                </div>
                <?php if ($flex->description() != '') { ?>
                    <div class="repeat__row__flex_description"><?= $flex->description() ?></div><?php } ?>
            </div>
            <?php
        }

        foreach ($fields_by_cols as $index => $cols) {
            $style = get_array();
            if (preg_match('/^[\d]+$/i', $widths_by_cols[$index])) {
                if (intval($widths_by_cols[$index]) > 100) {
                    $style->push('flex-basis', $widths_by_cols[$index] . 'px');
                } elseif (intval($widths_by_cols[$index]) > 10) {
                    $style->push('flex-basis', $widths_by_cols[$index] . '%');
                } else {
                    $style->push('flex-grow', $widths_by_cols[$index]);
                }
            } elseif ($widths_by_cols[$index] != '') {
                $style->push('flex-basis', $widths_by_cols[$index]);
            }

            ?>
            <div class="repeat__row__col" style="<?= $style->get_as_tag_style() ?>">
                <?php
                /**
                 * @var string           $col_id
                 * @var Field_Repeat_Col $col
                 */
                foreach ($cols as $col_id => $col) {
                    ?>
                    <div class="repeat__row__col__field_wrap" data-field_repeat-col_id="<?= $col_id ?>" data-unique_id="<?= $this->field()->get_unique_id() ?>">
                        <?php if ($col->label() != '') { ?>
                            <div class="repeat__row__col__label"><?= $col->label() ?></div><?php } elseif ($col->field()->options()->label() != '') { ?>
                            <div class="repeat__row__col__label"><?= $col->field()->options()->label() ?></div><?php } ?>
                        <?php if ($col->description() != '') { ?>
                            <div class="repeat__row__col__description"><?= $col->description() ?></div><?php } elseif ($col->field()->options()->description() != '') { ?>
                            <div class="repeat__row__col__description"><?= $col->field()->options()->description() ?></div><?php } ?>
                        <?= $col->field()->get_admin_html($this->get_col_input_value($col->ID())); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }

        ?>
    </div>
<?php
if (count($fields_hidden) > 0) {
    $rand_id = $this->field()->get_unique_id() . '-fields_hidden-' . \hiweb\core\Strings::rand(5);
    ?>
    <div class="repeat__row__options_button">
        <a title="<?= __('Options for this row', 'hiweb-core-4') ?>" data-unique_id="<?= $this->field()->get_unique_id() ?>" data-action="options"><?= get_fontawesome('fad fa-cog') ?></a>
    </div>
    <div style="display: none;">
        <a href="/?TB_inline&inlineId=<?= urlencode($rand_id) ?>&width=700&height=500" data-tb-inline-id="<?= $rand_id ?>" class="thickbox"></a>
        <div class="repeat__row__options_fields__outer" id="<?= $rand_id ?>">
            <div class="repeat__row__options_fields">
                <?php
                foreach ($fields_hidden as $col_id => $col) {
                    ?>
                    <div data-field_repeat-col_id="<?= $col_id ?>" data-unique_id="<?= $this->field()->get_unique_id() ?>">
                        <?php if ($col->label() != '') { ?>
                            <div class="repeat__row__label"><?= $col->label() ?></div><?php } ?>
                        <?= $col->field()->get_admin_html($this->get_col_input_value($col->ID())); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}