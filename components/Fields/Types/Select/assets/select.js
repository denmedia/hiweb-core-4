let hiweb_field_select = {

    init: function (element) {
        let $root = jQuery(element.target);
        if ($root.length > 0) {
            let $select = $root.find('select[name]');
            //let data_options = JSON.parse($select.attr('data-options'));
            $select.selectize({
                closeAfterSelect: true,
                allowEmptyOption: true,
                valueField: 'value',
                labelField: 'title',
                searchField: 'title',
                placeholder: $root.attr('placeholder'),
                options: [],
                plugins: ['remove_button', 'drag_drop']
            });
        }
    }

};


jQuery('body').on('field_init', '.hiweb-field_select', hiweb_field_select.init);