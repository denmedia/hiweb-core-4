/**
 * Created by denmedia on 08.06.2017.
 */


let hiweb_field_type_post = {

    init: function (element) {
        let $root = jQuery(element);
        if(!$root.is('.hiweb-field-type-post')){
            $root = $root.closest('.hiweb-field-type-post');
        }
        if($root.length === 0) {
            $root = jQuery(this);
        }
        if($root.length > 0 && !$root.is('.hiweb-field-type-post-init')) {
            $root.addClass('hiweb-field-type-post-init');
            hiweb_field_type_post.make_selectize($root);
        }
    },

    _load_posts: function ($select, query, callback) {
        if (typeof query !== 'string') query = '';
        let data_options = JSON.parse($select.attr('data-options'));
        jQuery.ajax({
            //url: ajaxurl + '?action=hiweb-type-post' + (typeof query === 'undefined') ? '' : '&' + encodeURIComponent(query),
            url: ajaxurl + '?action=hiweb-components-fields-type-post',
            type: 'POST',
            dataType: 'json',
            data: {global_id: $select.attr('data-global-id'), search: query, post_type: data_options.post_type},
            error: function () {
                if (callback === 'function') {
                    callback();
                }
            },
            success: function (res) {
                if (res.hasOwnProperty('success')) {
                    if (res.success && typeof callback === 'function') {
                        callback(res.items);
                    } else {
                        console.warn(res);
                    }
                } else {
                    console.error('hiweb-input-post: Не удалорсь загрузить список записей');
                }

            }
        });
    },

    make_selectize: function ($root) {
        let $select = $root.find('select[name]');
        let data_options = JSON.parse($select.attr('data-options'));
        $select.selectize({
            closeAfterSelect: true,
            allowEmptyOption: true,
            valueField: 'value',
            labelField: 'title',
            searchField: 'title',
            placeholder: $select.attr('placeholder'),
            options: [],
            plugins: ['remove_button','drag_drop'],
            create: false,
            onInitialize: function () {
                hiweb_field_type_post._load_posts($select, '', function (items) {
                    let value = jQuery.parseJSON($select.attr('data-value'));
                    $select.each(function () {
                        for (let index in items) {
                            this.selectize.addOption(items[index]);
                        }
                        this.selectize.addItem(value);
                    });
                });
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                hiweb_field_type_post._load_posts($select, query, callback);
            }
        });
    }

};

jQuery('.hiweb-field-post').each(hiweb_field_type_post.init);
jQuery('body').on('hiweb-form-ajax-loaded hiweb-field-repeat-added-row', '.hiweb-components-form-ajax-wrap', function () {
    jQuery(this).find('.hiweb-field-type-post').each(hiweb_field_type_post.init);
});