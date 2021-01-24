let hiweb_field_color = {

    init: function (e) {
        let $root = jQuery(e.target);
        let $input = $root.find(':input[name]');
        if ($input.length > 0 && typeof $input.spectrum === 'function') {
            $input.spectrum({
                type: "component",
                allowEmpty: true,
                hideAfterPaletteSelect: true,
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true"
            });
        }
    }

}

jQuery('body').on('field_init', '.hiweb-field_color', hiweb_field_color.init);