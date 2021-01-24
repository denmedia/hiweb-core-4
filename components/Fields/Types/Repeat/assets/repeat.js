jQuery(document).ready(function ($) {

    let hiweb_field_repeat = {

        selector: '.hiweb-field_repeat',

        qtips: {},
        sortables: {},

        lastRowIndex: {},

        init: function () {
            let $root = $(this);
            if (!$root.is('[data-unique_id]')) return;
            //button events
            hiweb_field_repeat.init_buttons($root);
            ///sortable
            hiweb_field_repeat.make_sortable($root);
            ///set input names
            hiweb_field_repeat.set_input_names($root);
        },

        last_row_index: function ($element, set) {
            let unique_id = $element.is('[data-unique_id]') ? $element.attr('data-unique_id') : $element.closest(hiweb_field_repeat.selector).attr('data-unique_id');
            if (set === undefined) {
                if (hiweb_field_repeat.lastRowIndex.hasOwnProperty(unique_id)) {
                    return hiweb_field_repeat.lastRowIndex[unique_id];
                } else {
                    return 0;
                }
            }
            ///set last index
            hiweb_field_repeat.lastRowIndex[unique_id] = set;
        },

        open_dropdown: function ($dropdown_button) {
            let unique_id = $dropdown_button.attr('data-unique_id');
            if ($dropdown_button.is('[data-index]')) hiweb_field_repeat.last_row_index($dropdown_button, $dropdown_button.attr('data-index'));
            //make qtip
            if (!hiweb_field_repeat.qtips.hasOwnProperty(unique_id)) {
                let $content = $('[data-dropdown-content="' + unique_id + '"]');
                hiweb_field_repeat.qtips[unique_id] = $content.qtip({
                    content: $content,
                    show: {
                        target: $dropdown_button,
                        event: 'click',
                        effect: function () {
                            jQuery(this).fadeIn(300); // "this" refers to the tooltip
                        }
                    },
                    hide: {
                        event: 'unfocus focusout mouseleave click mouseclick',
                        delay: 100,
                        effect: function () {
                            jQuery(this).fadeOut(400); // "this" refers to the tooltip
                        }
                    },
                    style: {
                        classes: 'qtip-light qtip-shadow qtip-hiweb-fields'
                    },
                    position: {
                        my: 'top center',
                        at: 'bottom center',
                        viewport: true,
                        adjust: {
                            method: 'shift flip'
                        }
                    }
                });
                $content.on('click', 'a', () => {
                    hiweb_field_repeat.qtips[unique_id].qtip('hide');
                });
            }
            hiweb_field_repeat.qtips[unique_id].qtip('show').qtip('option', 'position.target', $dropdown_button);
        },

        open_row_control: function ($row_button_click) {
            let unique_id = $row_button_click.attr('data-unique_id');
            let qtip_id = unique_id + '-row_control';
            hiweb_field_repeat.last_row_index($row_button_click, $row_button_click.closest('[data-row]').attr('data-row'));
            if (!hiweb_field_repeat.qtips.hasOwnProperty(qtip_id)) {
                let $content = $('[data-row-control="' + unique_id + '"]');
                hiweb_field_repeat.qtips[qtip_id] = $content.qtip({
                    content: $content,
                    show: {
                        target: $row_button_click,
                        event: 'click',
                        effect: function () {
                            jQuery(this).fadeIn(300);
                        }
                    },
                    hide: {
                        event: 'unfocus focusout mouseleave click mouseclick',
                        delay: 200,
                        effect: function () {
                            jQuery(this).fadeOut(400);
                        }
                    },
                    style: {
                        classes: 'qtip-light qtip-shadow qtip-hiweb-fields'
                    },
                    position: {
                        my: 'top right',
                        at: 'bottom center',
                        viewport: true,
                        adjust: {
                            method: 'shift flip'
                        }
                    }
                });
                $content.on('click', 'a', () => {
                    hiweb_field_repeat.qtips[qtip_id].qtip('hide');
                });
            }
            hiweb_field_repeat.qtips[qtip_id].qtip('show').qtip('option', 'position.target', $row_button_click);
        },

        init_buttons: function ($root) {
            let unique_id = $root.attr('data-unique_id');
            if (unique_id) {
                $('body').on('click', '[data-action][data-unique_id="' + unique_id + '"]', function (e) {
                    let $click_element = $(this);
                    switch ($click_element.attr('data-action')) {
                        case 'dropdown':
                            hiweb_field_repeat.open_dropdown($click_element);
                            break;
                        case 'row-control':
                            hiweb_field_repeat.open_row_control($click_element);
                            break;
                        case 'add':
                            hiweb_field_repeat.click_add($click_element);
                            break;
                        case 'remove':
                            hiweb_field_repeat.click_remove($click_element);
                            break;
                        case 'clear':
                            hiweb_field_repeat.click_clear_full($click_element);
                            break;
                        case 'duplicate':
                            hiweb_field_repeat.click_duplicate($click_element);
                            break;
                        case 'copy':
                            hiweb_field_repeat.click_copy($click_element);
                            break;
                        case 'paste':
                            hiweb_field_repeat.click_paste($click_element);
                            break;
                        case 'collapse':
                            hiweb_field_repeat.click_row_expand($click_element);
                            break;
                        case 'options':
                            hiweb_field_repeat.click_options($click_element);
                            break;
                    }
                    e.preventDefault();
                });
            }
        },

        set_input_names: function ($root, parent_name_sequence) {
            let unique_id = $root.attr('data-unique_id');
            if (parent_name_sequence === undefined) {
                parent_name_sequence = [$root.attr('data-field-input_name')];
            }
            ///rows
            let $rows = $root.find('[data-rows_list="' + unique_id + '"] > [data-row]');
            $root.attr('data-rows-count', $rows.length);
            let $message_wrap = $('.repeat__message_empty[data-unique_id="' + unique_id + '"]');
            if ($rows.length === 0) {
                $message_wrap.height($message_wrap.find('.repeat__message_empty_inner').outerHeight());
            } else {
                $message_wrap.height(0);
            }
            $rows.each(function () {
                let $row = $(this);
                $row.attr('data-row', $row.index());
                let row_name_sequence = parent_name_sequence.slice();
                row_name_sequence.push('[' + $row.index() + ']');
                ///Inputs
                let $input_wraps = $row.find('[data-field-input_name]').not(hiweb_field_repeat.selector + '[data-unique_id="' + unique_id + '"] ' + hiweb_field_repeat.selector + ' [data-field-input_name]');
                $input_wraps.each(function () {
                    let $input_wrap = $(this);
                    let input_name_sequence = row_name_sequence.slice();
                    input_name_sequence.push('[' + $input_wrap.attr('data-field-id') + ']');
                    let input_name = input_name_sequence.join('');
                    $input_wrap.attr('data-field-input_name', input_name);
                    if ($input_wrap.is(hiweb_field_repeat.selector)) {
                        hiweb_field_repeat.set_input_names($input_wrap);
                    } else {
                        let $inputs = $input_wrap.find(':input[data-input_name_source]');
                        if ($inputs.length === 0) $inputs = $input_wrap.find(':input[name]');
                        $inputs.each(function () {
                            let $input = $(this);
                            $input.attr('name', $input.is('[data-input_name_append]') ? (input_name + $input.attr('data-input_name_append')) : input_name);
                        });
                        $input_wrap.trigger('field_name_change');
                    }
                });
                ///
            });
        },

        click_copy: function ($click_element) {
            let $root = $(hiweb_field_repeat.selector + '[data-unique_id="' + $click_element.attr('data-unique_id') + '"]');
            let inputValues = {};
            if (hiweb_field_repeat.last_row_index($root) === '-1' || hiweb_field_repeat.last_row_index($root) === '+1') {
                inputValues = $root.find('[name]:input').serializeArray();
            } else {
                let $row = $('[data-row="' + hiweb_field_repeat.last_row_index($root) + '"][data-unique_id=' + $click_element.attr('data-unique_id') + ']');
                inputValues = $row.find('[name]:input').serializeArray();
            }
            let uniqueId = $root.attr('data-unique_id');
            //collect input values
            let values = {};
            let rootName = $root.attr('data-field-input_name');
            let namesCount = {};
            for (let i in inputValues) {
                let name = inputValues[i]['name'];
                if (name.indexOf(rootName) >= 0) {
                    //console.info(name + ' -> ' +);
                    name = name.substr(rootName.length).replace(/^\[(\d+)\]/, '$1');
                    if (name.match(/\[\]$/)) {
                        if (!namesCount.hasOwnProperty(name)) {
                            namesCount[name] = -1;
                        }
                        namesCount[name]++;
                        name = name.replace(/\[\]$/, '[' + namesCount[name] + ']');
                    }
                    values[name] = inputValues[i]['value'];
                }
            }
            values = hiweb_field_repeat.parseInputs(values);
            //
            $.ajax({
                url: ajaxurl + '?action=hiweb-field-repeat-get-row',
                type: 'post',
                dataType: 'json',
                data: {
                    id: $root.attr('data-field-global_id'),
                    method: 'copy',
                    input_name: $root.attr('data-field-input_name'),
                    value: values,
                    unique_id: uniqueId
                },
                success: function (response) {
                    if (response.hasOwnProperty('success') && response.success) {
                        $('.hiweb-field_repeat__row-control [data-action="copy"]').removeClass('disabled');
                    }
                }
            });
        },

        click_paste: function ($click_element) {
            let $root = $(hiweb_field_repeat.selector + '[data-unique_id="' + $click_element.attr('data-unique_id') + '"]');
            hiweb_field_repeat.add_rows($root, '::paste::', hiweb_field_repeat.last_row_index($root));
        },

        make_sortable: function ($root) {
            let unique_id = $root.attr('data-unique_id');
            let $rows_list = $('[data-rows_list="' + unique_id + '"]');
            if (typeof $rows_list.sortable == 'undefined') {
                alert('Плагин jquery.ui.sortable.js не подключен!');
                return;
            }
            //re-init old sortable
            if (hiweb_field_repeat.sortables.hasOwnProperty(unique_id)) {
                hiweb_field_repeat.sortables[unique_id].sortable('destroy');
            }
            ///
            let $connectedWith = $('[data-rows_list][data-field-global_id="' + $rows_list.attr('data-field-global_id') + '"]');
            ///
            hiweb_field_repeat.sortables[unique_id] = $rows_list.sortable({
                update: function (e, ui) {
                    $('[data-drag-handle="' + unique_id + '"]').each(function () {
                        $(this).attr('data-drag-handle', $(this).closest(hiweb_field_repeat.selector).attr('data-unique_id'));
                    });
                    hiweb_field_repeat.set_input_names($root);
                },
                tolerance: "intersect",
                forcePlaceholderSize: true,
                distance: 3,
                handle: '[data-drag-handle="' + unique_id + '"], [data-drag-handle="' + unique_id + '"] *',
                connectWith: $connectedWith,
                revert: true,
                start: function (e, elements) {
                    elements.placeholder.outerHeight(elements.helper.outerHeight());
                    let $connectedWith = $('[data-rows_list][data-field-global_id="' + $rows_list.attr('data-field-global_id') + '"]');
                    $rows_list.sortable("option", "connectWith", $connectedWith);
                    ///connect check
                    $connectedWith.each(function () {
                        let $connected_rows_list = $(this).closest(hiweb_field_repeat.selector).find('[data-rows_list]');
                        if ($connected_rows_list.find('> *').length === 0) {
                            $connected_rows_list.append('<div class="ui-sortable-placeholder" style="height: 50px;"></div>');
                        }
                    });
                },
                stop: function (e, ui) {
                    //ui.item.closest('table').removeClass('ui-sorting-process');
                    $connectedWith.each(function () {
                        $connectedWith.closest(hiweb_field_repeat.selector).find('[data-rows_list] > .ui-sortable-placeholder').remove();
                    });
                }
            });
        },

        click_add: function ($button) {
            let unique_id = $button.attr('data-unique_id');
            let flex_row_id = $button.is('[data-flex_id]') ? $button.attr('data-flex_id') : '';
            let $root = jQuery(hiweb_field_repeat.selector + '[data-unique_id="' + unique_id + '"]');
            hiweb_field_repeat.add_rows($root, flex_row_id, hiweb_field_repeat.last_row_index($root), '');
        },

        get_name_id: function (e) {
            return jQuery(e).is('[data-input_name]') ? jQuery(e).attr('data-input_name') : jQuery(e).closest('[data-input_name]').attr('data-input_name');
        },


        add_rows: function ($root, flexRowId_orValues, index, successCallback) {
            if (!$root.is(hiweb_field_repeat.selector)) return;
            let uniqueId = $root.attr('data-unique_id');
            ///defaults
            let $rowsList = $root.find('[data-rows_list="' + uniqueId + '"]');
            if (flexRowId_orValues === undefined) flexRowId_orValues = '';
            //if (!Number.isInteger(index)) index = $rowsList.find('> [data-row]').length;
            $root.attr('data-status', 'loading');
            ///ajax
            $.ajax({
                url: ajaxurl + '?action=hiweb-field-repeat-get-row',
                type: 'post',
                dataType: 'json',
                data: {
                    id: $root.attr('data-field-global_id'),
                    method: 'ajax_json_row',
                    input_name: $root.attr('data-field-input_name'),
                    index: index,
                    value: flexRowId_orValues,
                    unique_id: uniqueId
                },
                success: function (response) {
                    if (response.hasOwnProperty('success')) {
                        if (response.success) {
                            let $newRow = $(response.html);
                            ///insert row
                            $newRow.addClass('row_add collapsed');
                            if (index === '-1') {
                                $rowsList.prepend($newRow);
                            } else if (index === '+1') {
                                $rowsList.append($newRow);
                            } else {
                                let $currentRow = $rowsList.find('> [data-row="' + (index) + '"]');
                                $newRow.insertAfter($currentRow);
                            }
                            ///set input names
                            hiweb_field_repeat.set_input_names($root);
                            ///row fields init
                            $rowsList.find('[data-field-init="0"]').trigger('field_init').attr('data-field-init', '1');
                            ///sortable connect
                            if (typeof $rowsList.sortable === 'function') {
                                $rowsList.sortable("option", "connectWith", $('[data-rows_list][data-field-global_id="' + $rowsList.attr('data-field-global_id') + '"]'));
                            }
                            ///animate new row
                            $newRow.height($newRow.find('> .repeat__row_inner').outerHeight() + 'px').removeClass('row_add');
                            setTimeout(() => {
                                $newRow.height('');
                                $newRow.removeClass('collapsed');
                            }, 500);
                            if (typeof successCallback === 'function') successCallback(response);
                        } else {
                            alert(response.message);
                        }
                    } else {
                        alert('Fatal error...');
                    }
                },
                complete: () => {
                    $root.attr('data-status', 'loaded');
                }
            });
        },

        parseInputs: function (data) {
            let ret = {};
            retloop:
                for (let input in data) {
                    let val = data[input];

                    let parts = input.split('[');
                    let last = ret;

                    for (let i in parts) {
                        let part = parts[i];
                        if (part.substr(-1) == ']') {
                            part = part.substr(0, part.length - 1);
                        }
                        if (i == parts.length - 1) {
                            last[part] = val;
                            continue retloop;
                        } else if (!last.hasOwnProperty(part)) {
                            last[part] = {};
                        }
                        last = last[part];
                    }
                }
            return ret;
        },

        click_duplicate: function ($click_element) {
            let $row = $('[data-row="' + hiweb_field_repeat.last_row_index($click_element) + '"][data-unique_id=' + $click_element.attr('data-unique_id') + ']');
            let $root = $(hiweb_field_repeat.selector + '[data-unique_id="' + $click_element.attr('data-unique_id') + '"]');
            //collect input values
            let values = {};
            let inputValues = $row.find('[name]:input').serializeArray();
            let rootName = $root.attr('data-field-input_name');
            let namesCount = {};
            for (let i in inputValues) {
                let name = inputValues[i]['name'];
                if (name.indexOf(rootName) >= 0) {
                    //console.info(name + ' -> ' +);
                    name = name.substr(rootName.length).replace(/^\[(\d+)\]/, '$1');
                    if (name.match(/\[\]$/)) {
                        if (!namesCount.hasOwnProperty(name)) {
                            namesCount[name] = -1;
                        }
                        namesCount[name]++;
                        name = name.replace(/\[\]$/, '[' + namesCount[name] + ']');
                    }
                    values[name] = inputValues[i]['value'];
                }
            }
            //
            hiweb_field_repeat.add_rows($root, hiweb_field_repeat.parseInputs(values), $row.attr('data-row'));
        },

        click_remove: function ($button) {
            hiweb_field_repeat.do_remove_row($(hiweb_field_repeat.selector + '[data-unique_id="' + $button.attr('data-unique_id') + '"]'), hiweb_field_repeat.last_row_index($button));
        },

        do_remove_row: function ($root, index) {
            let $row = $root.find('[data-unique_id="' + $root.attr('data-unique_id') + '"][data-row="' + index + '"]');
            setTimeout(() => {
                $row.remove();
                hiweb_field_repeat.set_input_names($root);
            }, 500);
            $row.addClass('row_remove').height($row.find('> *').outerHeight()).height(0);
        },

        click_clear_full: function ($click_element) {
            let unique_id = $click_element.attr('data-unique_id');
            let $root = jQuery(hiweb_field_repeat.selector + '[data-unique_id="' + unique_id + '"]');
            if ($root.length === 0) return;
            if (confirm($root.attr('data-text_confirm_clear_all'))) {
                $('[data-row][data-unique_id="' + unique_id + '"]').each(function () {
                    hiweb_field_repeat.do_remove_row($root, $(this).attr('data-row'));
                });
            }
        },

        click_options: function ($click_element) {
            let unique_id = $click_element.attr('data-unique_id');
            let $row = $('[data-row="' + $click_element.closest('[data-row]').attr('data-row') + '"][data-unique_id="' + unique_id + '"]');
            let wrap_id = $row.find('.repeat__row__options_fields__outer').attr('id');
            if (typeof tb_show === 'function') {
                $('a[data-tb-inline-id="' + wrap_id + '"]').trigger('click');
            }
        },

        click_row_expand: function ($click_element) {
            let unique_id = $click_element.attr('data-unique_id');
            let $row = jQuery('[data-row="' + hiweb_field_repeat.lastRowIndex[unique_id] + '"][data-unique_id="' + unique_id + '"]');
            let is_dragged = $row.closest('.ui-sorting-process').length > 0;
            if (!is_dragged) {
                if ($row.is('.repeat__row__collapsed')) {
                    $row.removeClass('repeat__row__collapsed');
                    jQuery('[data-flex-row-collapsed-input="' + unique_id + '"]').val('0');
                } else {
                    $row.height($row.find(jQuery('> .repeat__row_inner > .repeat__row__fields').height()));
                    $row.addClass('repeat__row__collapsed');
                    $row.height('auto');
                    jQuery('[data-flex-row-collapsed-input="' + unique_id + '"]').val('1');
                }
            }
        },

        click_row_expand_all: function (e) {
            e.preventDefault();
            let $table_id = jQuery(this).attr('data-unique_id');
            let $rows = jQuery(hiweb_field_repeat.selector + '[data-unique_id="' + $table_id + '"] [data-row]');
            if ($rows.length !== $rows.filter('.row-collapsed').length) {
                $rows.addClass('row-collapsed');
                jQuery('[data-flex-row-collapsed-input="' + $table_id + '"]').val('1');
            } else {
                $rows.removeClass('row-collapsed');
                jQuery('[data-flex-row-collapsed-input="' + $table_id + '"]').val('0');
            }
        }

    };

    $('body').on('field_init', '.hiweb-field_repeat[data-field-init="0"]', hiweb_field_repeat.init);

});