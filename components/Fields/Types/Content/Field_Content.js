jQuery(document).ready(function ($) {

    let hiweb_fields_type_content = {

        tinymces: {},

        load_editor_html: function (rand_id) {
            let $field_wrap = $('.hiweb-field-type-content[data-rand-id="' + rand_id + '"]');
            let id = $field_wrap.attr('data-field-id');
            let global_id = $field_wrap.attr('data-field-global-id');
            if ($field_wrap.length > 0) {
                let $field_textarea = $field_wrap.find('> textarea[name]');
                let field_name = $field_textarea.attr('name');
                $.ajax({
                    url: ajaxurl + '?action=hiweb_components_fields_type_content_input',
                    type: 'post',
                    data: {id: id, global_id: global_id, rand_id: rand_id, name: field_name, value: $field_textarea.html()},
                    success: function (response) {
                        if (response.hasOwnProperty('success')) {
                            if (response.success) {
                                $field_textarea.remove();
                                $field_wrap.append(response.html);
                                hiweb_fields_type_content.make_editor(rand_id);
                            } else {
                                $field_wrap.append(response.message);
                            }
                        } else {

                        }

                    }
                });
            }
        },

        make_editor: function (id) {
            if (hiweb_fields_type_content.tinymces[id]) {
                let tinymceObject = tinymce.get(id);
                if (tinymceObject !== null)
                {
                    tinymceObject.destroy();
                }
            }
            let $field_wrap = jQuery('[data-rand-id="' + id + '"]');
            $field_wrap.addClass('hiweb-field-type-content-initialized');
            let $wrap = tinymce.$('#wp-' + id + '-wrap');
            let settings = {};
            Object.assign(settings, hiweb_fields_type_content_default_settings);
            settings.selector = '#' + id;

            if ($wrap.hasClass('tmce-active') || !settings.wp_skip_init) {
                hiweb_fields_type_content.tinymces[id] = tinymce.init(settings);
            }

            if (typeof quicktags !== 'undefined') {
                quicktags({id: id, buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,dfw"});
            }

            if (!window.wpActiveEditor) {
                window.wpActiveEditor = id;
            }


            ////WP Editor Mode Switch
            $('body').on('click', '#' + id + '-tmce', () => {
                let $wrap = $('#wp-' + id + '-wrap');
                let $tmce = $('#wp-' + id + '-wrap .mce-container');
                let $html = $('#wp-' + id + '-wrap .wp-editor-area');
                tinymce.get(id).setContent($html.val());
                $wrap.addClass('tmce-active').removeClass('html-active');
                $tmce.show();
                $html.hide();
            }).on('click', '#' + id + '-html', () => {
                let $wrap = $('#wp-' + id + '-wrap');
                let $tmce = $('#wp-' + id + '-wrap .mce-container');
                let $html = $('#wp-' + id + '-wrap .wp-editor-area');
                $html.val(tinymce.get(id).getContent());
                $wrap.addClass('html-active').removeClass('tmce-active');
                $tmce.hide();
                $html.show();
            });
        }

    };

    //TODO!
    $('body').on('hiweb-form-ajax-input-loaded hiweb-field-repeat-added-row-fadein', '.hiweb-field-type-content textarea[name]', function () {
        $(this).each(function () {
            hiweb_fields_type_content.load_editor_html($(this).attr('data-rand-id'));
        });
    }).on('hiweb-field-repeat-drag-stop', '.hiweb-field-type-content textarea[name]', function(){
        $(this).each(function () {
            hiweb_fields_type_content.make_editor($(this).attr('id'));
        });
    });
    $('.hiweb-field-type-content textarea[name]').each(function () {
        hiweb_fields_type_content.load_editor_html($(this).attr('data-rand-id'));
    });

});