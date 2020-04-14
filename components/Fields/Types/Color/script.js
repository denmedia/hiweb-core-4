jQuery(document).ready(function ($) {

    $('form').on('hiweb-form-ajax-input-loaded', function(){
        //$('.hiweb-field-type-color').not('hiweb-field-type-color')
        // $('.hiweb-field-type-color input[name]').spectrum({
        //     type: "component"
        // });
    });
    $('.hiweb-field-type-color input[name]').spectrum({
        type: "component",
        allowEmpty:true,
        togglePaletteOnly: "true",
        showInput: "true",
        showInitial: "true"
    });



});