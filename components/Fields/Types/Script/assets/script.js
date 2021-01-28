let hiweb_field_script = {

    editors: {},

    init: function (e) {
        let $root = jQuery(e.target);
        if (typeof CodeMirror === 'function') {
            let $input = $root.find('textarea[name]');
            if ($input.length > 0) {
                hiweb_field_script.editors[$root.attr('data-rand_id')] = CodeMirror.fromTextArea($input[0], {
                    lineNumbers: true,
                    mode: "htmlmixed",
                    selectionPointer: true,
                    extraKeys: {"Ctrl-Space": "autocomplete"},
                    lineWrapping: true,
                    foldGutter: true,
                    matchBrackets: true,
                    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
                });
            }
        }
    }

}

jQuery('body').on('field_init', '.hiweb-field_script', hiweb_field_script.init);