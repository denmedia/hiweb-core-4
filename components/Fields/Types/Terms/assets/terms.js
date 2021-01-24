let hiweb_field_terms = {

    init: function (element) {
        let $root = jQuery(element.target);
        let $select = $root.find('select[name]');
        //let data_options = JSON.parse($select.attr('data-options'));
        $select.selectize({
            closeAfterSelect: true,
            allowEmptyOption: true,
            valueField: 'value',
            labelField: 'title',
            searchField: 'title',
            placeholder: $select.attr('placeholder'),
            options: [],
            plugins: ['remove_button', 'drag_drop']
        });
    }

}

jQuery('body').on('field_init', '.hiweb-field_terms', hiweb_field_terms.init);