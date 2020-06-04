jQuery(document).ready(function ($) {

    let make_colorpicker = function () {
        let $color_inputs = $('.hiweb-field-type-color input[name]').not('.spectrum');
        if ($color_inputs.length > 0 && typeof $color_inputs.spectrum === 'function') {
            $color_inputs.spectrum({
                type: "component",
                allowEmpty: true,
                hideAfterPaletteSelect:true,
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true"
            });
        }
    };

    $('body').on('hiweb-field-repeat-added-row', '.hiweb-field-type-color input[name]', function () {
        make_colorpicker();
    }).on('hiweb-form-ajax-loaded', make_colorpicker);
    make_colorpicker();

});