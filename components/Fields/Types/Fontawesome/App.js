

let hiweb_field_fontawesome_make = function (element) {
    if (jQuery(element).is('input[name]')) element = jQuery(element).closest('.hiweb-field-type-fontawesome');
    jQuery(element).each(function () {
        let $root = jQuery(this);
        let $input = $root.find('input[name]');
        let $result = $root.find('[data-fontawesome-result-place]');
        let $styles = $root.find('[data-fontawesome-styles-place]');
        let $preview = $root.find('[data-icon-preview]');
        let $icon_loader = $root.find('[data-fontawesome-icon-loader]');
        let $icon_unknown = $root.find('[data-fontawesome-icon-unknown]');
        let input_keyup_timeout = 0;
        let result_qtip = $result.qtip({
            content: $result,
            style: {
                classes: 'qtip-light qtip-shadow'
            },
            position: {
                target: $input,
                my: 'top center',
                at: 'bottom center',
                adjust: {
                    method: 'shift none'
                }
            },
            events: {
                hide: function (event, api) {
                    $result.html('clear...');
                }
            }
        });
        let styles_qtip = $styles.qtip({
            content: $styles,
            style: {
                classes: 'qtip-light qtip-shadow'
            },
            position: {
                target: jQuery('[data-fontawesome-click="styles"]'),
                my: 'top center',
                at: 'bottom center',
                adjust: {
                    method: 'shift none'
                }
            },
            events: {
                hide: function (event, api) {
                    $result.html('clear...');
                }
            }
        });
        $root.on('click', '[data-fontawesome-click]', function (e) {
            e.preventDefault();
            let $this = jQuery(this);
            switch ($this.attr('data-fontawesome-click')) {
                case 'all':
                    hiweb_field_fontawesome_ajax_search($root, $result, '', result_qtip);
                    break;
                case 'styles':
                    hiweb_field_fontawesome_ajax_search($root, $result, $input.val(), result_qtip);
                    break;
                case 'clear':
                    hiweb_field_fontawesome_clear($root);
                    break;
                default:
                    console.warn('unknown button click!');
            }
        });
        $input.on('keyup', function () {
            clearTimeout(input_keyup_timeout);
            input_keyup_timeout = setTimeout(() => {
                clearTimeout(input_keyup_timeout);
                let search = $root.find('input[name]').val();
                hiweb_field_fontawesome_ajax_search($root, $result, search, result_qtip, input_keyup_timeout);
            }, 1000);
        });
    });
};

let hiweb_field_fontawesome_ajax_search = function ($root, $result, search, qtip_result, input_keyup_timeout) {
    //let search = $root.find('input[name]').val();
    //let $result = $root.find('[data-fontawesome-result-place]');
    let $preview = $root.find('[data-icon-preview]');
    let $icon_loader = $root.find('[data-fontawesome-icon-loader]');
    let $icon_unknown = $root.find('[data-fontawesome-icon-unknown]');
    ///
    $preview.html($icon_loader.html());
    jQuery.ajax({
        url: ajaxurl + '?action=hiweb_components_fields_type_fontawesome',
        type: 'post',
        dataType: 'json',
        data: {search: search},
        success: (response) => {
            if (!response.hasOwnProperty('success')) {
                console.error('ошибка во время получения ответа от сервера');
            } else {
                if (response.hasOwnProperty('html') && response.hasOwnProperty('result_count')) {
                    $preview.html($icon_unknown.html());
                    qtip_result.qtip('hide');
                    if (response.result_count > 0) {
                        if (response.result_count > 20) $result.attr('data-count', 'many');
                        else if (response.result_count > 10) $result.attr('data-count', 'medium');
                        else $result.attr('data-count', 'low');
                        $result.html(response.html);
                        $result.on('click', '[data-result-icon]', (e) => {
                            $root.attr('data-selected', '1');
                            e.preventDefault();
                            let $target = jQuery(e.currentTarget);
                            hiweb_fields_fontawesome_select($root, $target.attr('data-result-icon'), $target.html());
                            qtip_result.qtip('hide');
                            clearTimeout(input_keyup_timeout);
                        });
                        qtip_result.qtip('show');
                    }
                }
                //todo
            }
        }
    });
};

let hiweb_fields_fontawesome_select = function ($root, class_name, svg) {
    $root.find('input[name]').val(class_name);
    $root.find('[data-icon-preview]').html(svg);
};

let hiweb_field_fontawesome_clear = function ($root) {
    $root.find('input[name]').val('');
    $root.find('[data-icon-preview]').html($root.find('[data-fontawesome-icon-unknown]').html());
};

jQuery(document).ready(function ($) {

    hiweb_field_fontawesome_make('.hiweb-field-type-fontawesome');
   
});

jQuery('body').on('hiweb-form-ajax-loaded hiweb-field-repeat-added-row', '.hiweb-components-form-ajax-wrap', function () {
    jQuery(this).find('.hiweb-field-type-fontawesome').each(function(){
        hiweb_field_fontawesome_make(jQuery(this));
    });
});