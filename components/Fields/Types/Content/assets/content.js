jQuery(document).ready(function ($) {

    let hiweb_fields_type_content = {

        tinymces: {},

        init: function (e) {
            let $root = $(e.target);
            //hiweb_fields_type_content.load_editor_html($root.attr('data-rand-id'));
            let $editor_wrap = $root.find('> [data-editor_wrap="' + $root.attr('data-rand_id') + '"]')
            let $input = $('#' + $root.attr('data-rand_id'));
            let tinyMceSettings = Object.assign({
                selector: '[data-editor_wrap="' + $root.attr('data-rand_id') + '"]',
                init_instance_callback: function (editor) {
                    editor.on('Change', function (e) {
                        $input.html(editor.getContent()).trigger('change');
                        if ($input.text() === '') {
                            $root.addClass('empty');
                        } else {
                            $root.removeClass('empty');
                        }
                    });
                }
            }, hiweb_fields_type_content_default_settings);
            //let $mceInput = $root.find()
            ///
            let mce = tinymce.init(tinyMceSettings);
        },

        make_editor: function (id) {
            if (hiweb_fields_type_content.tinymces[id]) {
                let tinymceObject = tinymce.get(id);
                if (tinymceObject !== null) {
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

    $('body').on('field_init', '.hiweb-field_content', hiweb_fields_type_content.init);


});