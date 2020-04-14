let hiweb_fields_type_content = {

    add_editor: function (id) {
        let $field_wrap = jQuery('[data-field-id="' + id + '"]');
        if ($field_wrap.is('.hiweb-field-type-content-initialized')) return;
        $field_wrap.addClass('hiweb-field-type-content-initialized');
        $wrap = tinymce.$('#wp-' + id + '-wrap');
        let settings = {};
        Object.assign(settings, hiweb_fields_type_content_default_settings);
        settings.selector = '#' + id;

        if ($wrap.hasClass('tmce-active') || !settings.wp_skip_init) {
            tinymce.init(settings);
        }

        if (typeof quicktags !== 'undefined') {
            quicktags({id: id, buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,dfw"});
        }

        if (!window.wpActiveEditor) {
            window.wpActiveEditor = id;
        }
    }

};


jQuery(document).ready(function ($) {
    $('.hiweb-field-type-content').each(function () {
        window.hiweb_fields_type_content.add_editor($(this).attr('data-field-id'));
    });
    $('body').on('hiweb-components-form-ajax-loaded', function () {
        $('.hiweb-field-type-content').each(function () {
            window.hiweb_fields_type_content.add_editor($(this).attr('data-field-id'));
        });
    });
});