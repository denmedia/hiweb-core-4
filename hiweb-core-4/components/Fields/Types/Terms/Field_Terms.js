let hiweb_field_type_terms = {

    init: function(element){
        let $root = jQuery(element);
        if(!$root.is('.hiweb-field-type-terms')){
            $root = $root.closest('.hiweb-field-type-terms');
        }
        if($root.length === 0) {
            $root = jQuery(this);
        }
        if($root.length > 0 && !$root.is('.hiweb-field-type-terms-init')) {
            $root.addClass('hiweb-field-type-terms-init');
            hiweb_field_type_terms.make_selectize($root);
        }
    },

    make_selectize: function ($root) {
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
            plugins: ['remove_button','drag_drop']
        });
    }

}

jQuery('.hiweb-field-post').each(hiweb_field_type_terms.init);
jQuery('body').on('hiweb-form-ajax-loaded hiweb-field-repeat-added-row', '.hiweb-components-form-ajax-wrap', function () {
    jQuery(this).find('.hiweb-field-type-terms').each(hiweb_field_type_terms.init);
});