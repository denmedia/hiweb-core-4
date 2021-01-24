let hiweb_field_fontawesome = {

    qtipInstance: null,
    findTimeout: 1000,
    findTimeoutN: 0,

    init: function (e) {
        let $root = jQuery(e.target);
        if ($root.length > 0) {
            hiweb_field_fontawesome.make_events($root);
        }
    },

    make_events: function ($root) {
        $root.on('click', '[data-click]', function (e) {
            e.preventDefault();
            let $click_button = jQuery(this);
            switch ($click_button.attr('data-click')) {
                case 'find' :
                    hiweb_field_fontawesome.find($root, true);
                    break;
                case 'all' :
                    hiweb_field_fontawesome.show_all($root);
                    break;
                case 'styles' :
                    hiweb_field_fontawesome.show_styles($root);
                    break;
                case 'clear' :
                    hiweb_field_fontawesome.click_clear($root);
                    break;
            }
        });
        $root.find(':input[name]').on('keyup', () => {
            hiweb_field_fontawesome.find($root);
        });
    },

    find: function ($root, instantly) {
        let $input = $root.find(':input[name]');
        $root.attr('data-input_empty', $input.val() === '' ? '1' : '0');
        if ($input.val() === '') $root.attr('data-selected', '0');
        if (instantly === undefined) instantly = false;
        clearTimeout(hiweb_field_fontawesome.findTimeoutN);
        hiweb_field_fontawesome.findTimeoutN = setTimeout(() => {
            if($input.val() === '') return;
            let $drop_down = hiweb_field_fontawesome.open_dropdown($root);
            $drop_down.attr('data-status', 'loading');
            hiweb_field_fontawesome.get_data($root, $root.find(':input[name]').val(), (response) => {
                $drop_down.attr('data-status', 'loaded');
                if (response.hasOwnProperty('icons') && typeof response.icons === 'object') {
                    let firstIcon = true;
                    for (let iconClass in response.icons) {
                        $drop_down.append('<span data-icon="' + iconClass + '">' + response.icons[iconClass] + '</span>');
                        if (firstIcon && response.count === 1) {
                            if (!instantly) {
                                $root.find('[data-icon-preview]').html(response.icons[iconClass]);
                                $input.val(iconClass);
                            }
                        }
                        firstIcon = false;
                    }
                }
            });
        }, instantly !== true ? hiweb_field_fontawesome.findTimeout : 10);
    },

    show_all: function ($root) {
        let $drop_down = hiweb_field_fontawesome.open_dropdown($root);
        $drop_down.attr('data-status', 'loading');
        hiweb_field_fontawesome.get_data($root, '::all', (response) => {
            $drop_down.attr('data-status', 'loaded');
            if (response.hasOwnProperty('icons') && typeof response.icons === 'object') {
                for (let iconClass in response.icons) {
                    $drop_down.append('<span data-icon="' + iconClass + '">' + response.icons[iconClass] + '</span>');
                }
            }
        });
    },

    show_styles: function ($root) {
        let $drop_down = hiweb_field_fontawesome.open_dropdown($root);
        let $input = $root.find(':input[name]');
        $drop_down.attr('data-status', 'loading');
        hiweb_field_fontawesome.get_data($root, '::style::' + $input.val(), (response) => {
            $drop_down.attr('data-status', 'loaded');
            if (response.hasOwnProperty('icons') && typeof response.icons === 'object') {
                for (let iconClass in response.icons) {
                    $drop_down.append('<span data-icon="' + iconClass + '">' + response.icons[iconClass] + '</span>');
                }
            }
        });
    },

    click_clear: function ($root) {
        $root.attr('data-input_empty', '1').attr('data-selected', '0');
        $root.find(':input[name]').val('');
        $root.find('[data-icon-preview]').html($root.find('[data-fontawesome-icon-unknown]').html());
    },

    // dropdown_set_loading: function(){
    //
    // },

    /**
     * Return $drop_down element
     * @param $root
     * @returns {*|jQuery|HTMLElement}
     */
    open_dropdown: function ($root) {
        //make qtip
        let $drop_down = jQuery('<div class="hiweb-field_fontawesome__dropdown"></div>');
        let $input = $root.find(':input[name]');
        hiweb_field_fontawesome.qtipInstance = $input.qtip({
            content: $drop_down,
            show: {
                ready: true,
                solo: true,
                target: $input,
                event: 'click',
                effect: function () {
                    jQuery(this).fadeIn(300); // "this" refers to the tooltip
                }
            },
            hide: {
                event: 'unfocus focusout mouseleave click mouseclick',
                delay: 100,
                effect: function () {
                    jQuery(this).fadeOut(400); // "this" refers to the tooltip
                }
            },
            style: {
                classes: 'qtip-light qtip-shadow qtip-hiweb-fields'
            },
            position: {
                my: 'top center',
                at: 'bottom center',
                viewport: true,
                adjust: {
                    method: 'shift flip'
                }
            },
            events: {
                hide: () => {
                    hiweb_field_fontawesome.qtipInstance.qtip('destroy');
                }
            }
        });
        $drop_down.on('click', '[data-icon]', function () {
            $root.find('[data-icon-preview]').html(jQuery(this).html());
            $input.val(jQuery(this).attr('data-icon'));
            $root.attr('data-input_empty', '0').attr('data-selected', '1');
            hiweb_field_fontawesome.qtipInstance.qtip('hide');
        });
        ///
        return $drop_down;
    },

    get_data: function ($root, query, successFn) {
        $root.attr('data-status', 'loading');
        if (query === undefined) query = '';
        jQuery.ajax({
            url: hiweb_components_field_type_fontawesome_ajaxUrl,
            type: 'post',
            data: {query: query},
            dataType: 'json',
            success: function (response) {
                if (response.hasOwnProperty('success')) {
                    if (response.success) {
                        if (typeof successFn === 'function') {
                            successFn(response);
                        }
                    }
                }
            },
            complete: () => {
                $root.attr('data-status', 'loaded')
            }
        });
    }

}


jQuery('body').on('field_init', '.hiweb-field_fontawesome', hiweb_field_fontawesome.init);