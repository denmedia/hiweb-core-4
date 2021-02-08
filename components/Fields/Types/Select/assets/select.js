let hiweb_field_select = {

    init: function (element) {
        let $root = jQuery(element.target);
        if ($root.length > 0) {
            let $select = $root.find('select[name]');
            let data_options = $select.is('[data-options]') ? JSON.parse($select.attr('data-options')) : [];
            let properties = {
                closeAfterSelect: true,
                allowEmptyOption: $select.attr('data-allow_empty') === '1',
                options: data_options,
                plugins: $select.is('[multiple]') ? ['remove_button', 'drag_drop'] : []
            };
            if ($select.is('[data-max_items]') && !isNaN(parseInt($select.attr('data-max_items')))) {
                properties['maxItems'] = parseInt($select.attr('data-max_items'));
            }
            $select.selectize(properties);
        }
    }

};

jQuery('body').on('field_init', '.hiweb-field_select', hiweb_field_select.init);