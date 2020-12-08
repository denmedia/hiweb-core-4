/**
 * Created by hiweb on 21.10.2016.
 */

let hiweb_field_repeat = {

    selector: '.hiweb-field-type-repeat',
    selector_source: '[data-row-source]',
    selector_wrap: 'tbody.wrap',
    selector_row: '[data-row]',
    selector_button_add: '[data-action-add]',
    selector_button_clear: '[data-action-clear]',
    selector_button_remove: '[data-action-remove]',
    selector_button_duplicate: '[data-action-duplicate]',

    init: function (root) {
        let $root = jQuery(root);
        if ($root.length === 0) return;
        if (!$root.is('[data-unique_id]')) return;
        if ($root.is('.hiweb-field-type-repeat-init')) return;
        $root.addClass('hiweb-field-type-repeat-init');
        //button events
        hiweb_field_repeat.init_buttons($root);
        ///sortable
        hiweb_field_repeat.init_sortable($root);
        ///dropdown
        hiweb_field_repeat.init_dropdown($root);
        ///set input names
        hiweb_field_repeat.set_input_names($root);
    },


    init_buttons: function ($rootOrRow) {
        let unique_id = $rootOrRow.attr('data-unique_id');
        $rootOrRow.find('[data-action_add][data-unique_id="' + unique_id + '"]').on('click', hiweb_field_repeat.click_add);
        $rootOrRow.on('click', '[data-action-remove][data-unique_id="' + unique_id + '"]', hiweb_field_repeat.click_remove);
        $rootOrRow.find('[data-action_clear][data-unique_id="' + unique_id + '"]').on('click', hiweb_field_repeat.click_clear_full);
        $rootOrRow.on('click', '[data-drag-handle="' + unique_id + '"]', hiweb_field_repeat.click_row_expand);
        $rootOrRow.find('[data-action_collapse][data-unique_id="' + unique_id + '"]').on('click', hiweb_field_repeat.click_row_expand_all);
        $rootOrRow.on('click', '[data-action-duplicate][data-unique_id="' + unique_id + '"]', hiweb_field_repeat.click_duplicate);
    },

    init_dropdown: function ($root) {
        let unique_id = $root.attr('data-unique_id');
        //TODO!
        $root.find('[data-dropdown="' + unique_id + '"]').each(function () {
            let $drop_down = jQuery(this).find('.hiweb-fields-dropdown-menu');
            let qtip = jQuery(this).qtip({
                content: $drop_down,
                show: {
                    event: 'click',
                    effect: function(offset) {
                        jQuery(this).fadeIn(400); // "this" refers to the tooltip
                    }
                },
                hide: {
                    event: 'unfocus click mouseclick',
                    delay: 100,
                    effect: function(offset) {
                        jQuery(this).fadeOut(400); // "this" refers to the tooltip
                    }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
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
            $drop_down.on('click', 'a', () => {
                qtip.qtip('hide');
            })
        });
    },

    init_sortable: function (root) {
        let rows = hiweb_field_repeat.get_rows_list(root);
        if (typeof rows.sortable == 'undefined') {
            alert('Плагин jquery.ui.sortable.js не подключен!');
            return;
        }
        if (rows['sortable'].hasOwnProperty('destroy')) {
            rows.sortable("destroy");
        }
        rows.sortable({
            update: function (e, ui) {
                hiweb_field_repeat.set_input_names(jQuery(this).closest(hiweb_field_repeat.selector));
                ui.placeholder.find('[data-col]').each(function () {
                    jQuery(this).trigger('hiweb-field-repeat-drag-update', [jQuery(this), jQuery(this).closest('[data-row]'), root]);
                });
            },
            tolerance: "pointer",
            forcePlaceholderSize: true,
            distance: 3,
            handle: '> [data-drag-handle], > [data-drag-handle] button, > [data-drag-handle] i, > [data-drag-handle] svg',
            connectWith: '[data-rows_list="' + rows.attr('data-rows_list') + '"]',
            helper: function (e, ui) {
                ui.find('th, td').each(function () {
                    jQuery(this).width(jQuery(this).width());
                });
                ui.find('[data-col]').each(function () {
                    jQuery(this).trigger('hiweb-field-repeat-dragged', [jQuery(this), jQuery(this).closest('[data-row]'), root]);
                });
                return ui;
            },
            revert: true,
            start: function (e, elements) {
                elements.placeholder.outerHeight(elements.helper.outerHeight());
                ///SET SCALE OFFSET POINT
                let $parent_table = elements.placeholder.closest('table');
                let $repeat_wrap = elements.placeholder.closest('.hiweb-field-type-repeat');
                $parent_table.addClass('ui-sorting-process');
                //elements.placeholder.offset
                $repeat_wrap.css({'perspective-origin': '50% ' + $repeat_wrap.offset().top + 'px'});
                elements.helper.find('input[name], select[name], textarea[name], file[name]').trigger('hiweb-field-repeat-drag-start');
            },
            stop: function (e, ui) {
                ui.item.closest('table').removeClass('ui-sorting-process');
                ui.item.find('input[name], select[name], textarea[name], file[name]').trigger('hiweb-field-repeat-drag-stop');
            }
        });
    },


    click_add: function (e) {
        e.preventDefault();
        let $button = jQuery(this);
        let prepend = $button.is('[data-action_add="1"]');
        let unique_id = $button.attr('data-unique_id');
        let flex_row_id = $button.is('[data-flex_id]') ? $button.attr('data-flex_id') : '';
        let $root = jQuery('.hiweb-field-type-repeat[data-unique_id="' + unique_id + '"]');
        hiweb_field_repeat.add_rows($root, prepend, 1, '', flex_row_id, unique_id);
    },


    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_row_source: function (root) {
        return jQuery(root).find('> table > tbody[data-rows-source] > tr[data-row]');
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_rows_list: function (root) {
        return jQuery(root).find('> table > tbody[data-rows_list]');
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_rows: function (root) {
        return jQuery(root).find('> table > tbody[data-rows_list] > tr[data-row]');
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_cols: function (root) {
        return jQuery(root).find('> table > thead [data-col]');
    },

    /**
     *
     * @param row
     * @returns {*}
     */
    get_cols_by_row: function (row) {
        return jQuery(row).find('> [data-col], > td > [data-col], > td > table > tbody > tr > [data-col]');
    },

    getRandomColor: function () {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    },

    set_input_names: function (root) {
        root = jQuery(root);
        //set rows index
        root.find('[data-row]').each(function () {
            let $row = jQuery(this);
            let $root = $row.closest('.hiweb-field-type-repeat');
            jQuery(this).attr('data-row', $root.find('> table > tbody > tr[data-row]').index($row));
        });
        //set sub-input names
        var $sub_inputs = root.find(':input[name]');
        $sub_inputs.each(function () {
            jQuery(this).attr('name', hiweb_field_repeat.get_input_name(jQuery(this)));
        });
        //
        root.find('> table > tbody[data-rows_message] [data-row_empty]').attr('data-row_empty', hiweb_field_repeat.get_rows(root).length > 0 ? '1' : '0');
    },

    get_input_name: function ($input) {
        $input = jQuery($input);
        let name_items = [];
        let depth = 10;
        while (depth > 0) {
            var $col = $input.closest('[data-col]');
            if ($col.length !== 1) {
                return $input.closest('.hiweb-field-type-repeat[data-global_id]').attr('data-global_id');
            }
            //name sufix extract
            let parent_name = $input.is('.hiweb-field-type-repeat') ? $input.attr('data-id') : $input.attr('name');
            let name_segments = parent_name.split('[' + $col.attr('data-col') + ']');
            if (name_segments.length > 1) {
                let name_sufix = name_segments.pop();
                if (name_sufix !== '') {
                    name_items.push(name_sufix);
                }
            }
            name_items.push('[' + $col.attr('data-col') + ']');
            //row
            var $row = $col.closest('[data-row]');
            if ($row.length !== 1) {
                console.warn('2: row not found');
                break;
            }
            name_items.push('[' + $row.attr('data-row') + ']');
            //repeat or super col
            var $repeat = $row.closest('.hiweb-field-type-repeat[data-global_id]');
            if ($repeat.length !== 1) {
                console.warn('3: repeat not found');
            }
            var $super_col = $repeat.closest('[data-col]');
            if ($super_col.length !== 1) {
                //name_items.push($repeat.attr('data-id'));
                name_items.push($repeat.attr('data-input_name'));
                break;
            } else {
                $input = $repeat;
            }
            depth--;
        }
        return name_items.reverse().join('');
    },


    get_global_id: function (e) {
        return jQuery(e).is('[data-global_id]') ? jQuery(e).attr('data-global-_d') : jQuery(e).closest('[data-global_id]').attr('data-global_id');
    },

    get_name_id: function (e) {
        return jQuery(e).is('[data-input-name]') ? jQuery(e).data('input-name') : jQuery(e).closest('[data-input-name]').data('input-name');
    },

    add_rows: function ($root, prepend, rows_count, callback, flex_row_id, unique_id) {
        if (!$root.is('.hiweb-field-type-repeat')) return;
        ///
        if (typeof rows_count === 'undefined') rows_count = 1;
        if (typeof prepend === 'undefined') prepend = false;
        if (typeof flex_row_id === 'undefined') flex_row_id = '';
        ///
        var row_list = hiweb_field_repeat.get_rows_list($root);
        var index = prepend ? 0 : hiweb_field_repeat.get_rows($root).length;
        jQuery.ajax({
            url: ajaxurl + '?action=hiweb-field-repeat-get-row',
            type: 'post',
            data: {
                id: $root.attr('data-global_id'),
                method: 'ajax_html_row',
                input_name: hiweb_field_repeat.get_name_id($root),
                index: index,
                flex_row_id: flex_row_id,
                unique_id: unique_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.hasOwnProperty('result') && response.result === true) {
                    let $newLine = jQuery(response.data).hide().fadeIn();
                    for (let n = 0; n < rows_count; n++) {
                        if (prepend) {
                            row_list.prepend($newLine);
                        } else {
                            row_list.append($newLine);
                        }
                        ///animate / trigger
                        $newLine.find('> td')
                            .wrapInner('<div style="display: none;" />')
                            .parent()
                            .find('> td > div')
                            .slideDown(400, function () {
                                let $set = jQuery(this);
                                let $td = $set.parent();
                                $set.replaceWith($set.contents());
                                $td.find('input[name], select[name], textarea[name], file[name]').trigger('hiweb-field-repeat-added-row-fadein');
                            });
                        hiweb_field_repeat.set_input_names($root);
                        hiweb_field_repeat.init_buttons($newLine);
                        $newLine.closest('.hiweb-components-form-ajax-wrap').trigger('hiweb-field-repeat-added-row');
                        $newLine.find('input[name], select[name], textarea[name], file[name]').trigger('hiweb-field-repeat-added-row');
                    }
                } else {
                    console.warn(response);
                }
            },
            error: function (response) {
                console.warn(response);
            }
        });
    },

    paramsToArray: function (name, value) {
        var keys = name.match(/(\[?[\d\w\-\_]+\]?)/g);
        var R = temp = {};
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i].replace(/^\[?([\d\w\-\_]+)\]?/, '$1');
            if (i === keys.length - 1) {
                temp = temp[key] = value;
            } else {
                temp = temp[key] = {};
            }
        }
        return R;
    },


    click_duplicate: function (e) {
        e.preventDefault();
        let $row = jQuery(this).closest('tr');
        $row.after( $row.clone(true) );
        hiweb_field_repeat.set_input_names( $row.closest( hiweb_field_repeat.selector ) );
    },

    click_remove: function (e) {
        e.preventDefault();
        var row = jQuery(this).closest(hiweb_field_repeat.selector_row);
        hiweb_field_repeat.do_remove_row(row);
    },

    do_remove_row: function (row) {
        return jQuery(row).find('> td')
            .wrapInner('<div style="display: block;" />')
            .parent()
            .find('> td > div')
            .slideUp(700, function () {
                let $root = row.closest('.hiweb-field-type-repeat');
                jQuery(this).parent().parent().remove();
                row.remove();
                hiweb_field_repeat.set_input_names($root);
                if (hiweb_field_repeat.get_rows($root).length === 0) {
                    jQuery($root).find('[data-row_empty]').attr('data-row_empty','0');
                }
            });
    },

    click_clear_full: function (e) {
        e.preventDefault();
        let unique_id = jQuery(this).attr('data-unique_id');
        let $root = jQuery('.hiweb-field-type-repeat[data-unique_id="' + unique_id + '"]');
        if ($root.length === 0) return;
        if (confirm($root.attr('data-text_confirm_clear_all'))) {
            hiweb_field_repeat.get_rows($root).each(function () {
                hiweb_field_repeat.do_remove_row(jQuery(this));
            });
        }
    },

    click_row_expand: function(e){
        let unique_id = jQuery(this).attr('data-drag-handle');
        let $row = jQuery(this).closest('[data-row][data-unique_id="'+unique_id+'"]');
        let is_dragged = jQuery(this).closest('.iu-sorting-process').length > 0;
        if(!is_dragged) {
            if($row.is('.row-collapsed')) {
                $row.removeClass('row-collapsed');
                jQuery('[data-flex-row-collapsed-input="'+unique_id+'"]').val('0');
            } else {
                $row.addClass('row-collapsed');
                jQuery('[data-flex-row-collapsed-input="'+unique_id+'"]').val('1');
            }
        }
    },

    click_row_expand_all: function(e){
        e.preventDefault();
        let $table_id = jQuery(this).attr('data-unique_id');
        let $rows = jQuery('.hiweb-field-type-repeat[data-unique_id="'+$table_id+'"] [data-row]');
        if($rows.length !== $rows.filter('.row-collapsed').length) {
            $rows.addClass('row-collapsed');
            jQuery('[data-flex-row-collapsed-input="'+$table_id+'"]').val('1');
        } else {
            $rows.removeClass('row-collapsed');
            jQuery('[data-flex-row-collapsed-input="'+$table_id+'"]').val('0');
        }
    }

};

jQuery('.hiweb-field-type-repeat').each(function () {
    hiweb_field_repeat.init(this);
});
jQuery('body').on('hiweb-form-ajax-loaded hiweb-field-repeat-added-row', '.hiweb-components-form-ajax-wrap', function () {
    jQuery(this).find('.hiweb-field-type-repeat').each(function () {
        hiweb_field_repeat.init(this);
    });
});