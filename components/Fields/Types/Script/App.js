jQuery(document).ready(function ($) {

    let hiweb_field_type_script = function (root) {
        let $root = $(root);
        let $input = $root.find('textarea[name]');
        if ($input.length > 0) {
            if (typeof wp.CodeMirror === 'function') {
                let editor = wp.CodeMirror.fromTextArea($input[0], {
                    lineNumbers: true,
                    mode: "javascript",
                    theme: "blackboard"
                });
            }
        }

    };


    $('.hiweb-field-type-script').each(function () {
        hiweb_field_type_script(this);
    });

    jQuery('body').on('hiweb-form-ajax-loaded','.hiweb-components-form-ajax-wrap', function () {
        $(this).find('.hiweb-field-type-script').each(function () {
            hiweb_field_type_script(this);
        });
    });

});