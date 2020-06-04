jQuery(document).ready(function ($) {

    let app = {

        taxonomies: [],

        init: function () {
            app.taxonomies = hiweb_tools_taxonomy_main_select;
            app.make_checkboxes();
        },

        make_checkboxes: function () {
            for (let index in app.taxonomies) {
                let $meta_box_root = $('#' + app.taxonomies[index] + 'checklist');
                if ($meta_box_root.length > 0) {
                    $meta_box_root.addClass('hiweb-tools-taxonomy_main_select');
                    $meta_box_root.find('li input[type="checkbox"]').each(function () {
                        let $input = $(this);
                        let $li = $input.closest('li');
                        if ($input.prop('checked')) {
                            $li.attr('data-checked', $input.prop('checked') ? '1' : '0');
                        }
                        $input.on('change', function () {
                            $input.closest('li').attr('data-checked', $input.prop('checked') ? '1' : '0');
                        });
                        let $selector = $('<button data-taxonomy-selector/>').html('<i data-icon="0" class="dashicons dashicons-marker"></i><i data-icon="1" class="dashicons dashicons-yes-alt"></i>').attr('title', 'Сделать основным');
                        $selector.insertAfter( $li.attr('data-main-term-select', '0').find('> label') );
                        $selector.on('click', function (e) {
                            e.preventDefault();
                            app.set_main($li);
                        });
                    });
                    app.get_main($meta_box_root);
                }
            }
        },

        get_main: function ($meta_box_root) {
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {action: 'hiweb-tools-taxonomy_main_select-get', taxonomy: $meta_box_root.attr('data-wp-lists'), post_id: $('input[name="post_ID"]').val()},
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.data !== '') {
                        $('#' + response.data).attr('data-main-term-select', '1');
                    }
                }
            });
        },

        set_main: function ($li) {
            let id = $li.attr('id');
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {action: 'hiweb-tools-taxonomy_main_select-set', term_id: id, post_id: $('input[name="post_ID"]').val()},
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $li.closest('ul[data-wp-lists]').find('li').attr('data-main-term-select', '0');
                        $li.attr('data-main-term-select', '1');
                    }
                }
            });
        }
    };


    app.init();

});