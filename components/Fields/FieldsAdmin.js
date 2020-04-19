jQuery(document).ready(function ($) {

    let notify_max_input_vars_excess = function (inputs_length, max_input_vars) {
        if (typeof Noty === 'function') {
            new Noty({
                text: '<big><b>Количество передаваемых данных превышено!</b></big><br>Сохранение (обновление) приведет к потере части этих данных, включая старые.<br>На странице ' + inputs_length + ' полей, но на хостинге стоит ограничение в php.ini <b>max_input_vars = ' + max_input_vars + '</b>.<br>Попробуйте поместить в файл <u>.htaccess</u> следующую строчку <b>php_value max_input_vars 2000</b>',
                type: 'error',
                closeWith: ['button'],
                timeout: 60000,
                layout: 'top',
                animation: {
                    open: 'animated fadeInDown',
                    close: 'animated fadeOutUp'
                },
                theme: 'bootstrap-v3'
            }).show();
        } else {
            alert("Количество передаваемых данных превышено!\nСохранение (обновление) приведет к потере части этих данных, включая старые.\nНа странице " + inputs_length + " полей, но на хостинге стоит ограничение в php.ini max_input_vars = " + max_input_vars + ".\nПопробуйте поместить в файл .htaccess следующую строчку: php_value max_input_vars 2000");
        }
    };

    let load_form_scripts = function (response) {
        for (let handle in response.js) {
            let src = response.js[handle];
            if ($("script:regex(src, " + src + ")").length === 0 && $('script[data-handle="' + handle + '"]').length === 0) {
                $('body').append(
                    $('<script/>').attr('type', 'text/javascript').attr('defer', '').attr('src', src).attr('data-handle', handle).attr('data-ajax-loader', '')
                );
            }
        }
        for (let handle in response.css) {
            if ($('link[data-handle="' + handle + '"]').length === 0) {
                $('head').append(
                    $('<link/>').attr('rel', 'stylesheet').attr('type', 'text/css').attr('href', response.css[handle]).attr('data-handle', handle).attr('data-ajax-loader', '')
                );
            }
        }
    };

    let load_form_inputs = function ($form_wrap, response) {
        let new_inputs = $(response.form_html);
        let $form_inner = $form_wrap.find('.hiweb-components-form-ajax-inner');
        $form_inner.append(new_inputs);
        new_inputs.find('input[name], select[name],textarea[name],file[name]').trigger('hiweb-form-ajax-input-loaded').trigger('hiweb-form-updated');
        setInterval(() => {
            $form_wrap.height($form_inner.outerHeight());
        }, 500);
    };

    let $ajax_forms = $('.hiweb-components-form-ajax-wrap[data-fields-query][data-fields-query-id]');
    let max_input_vars = 0;
    let max_input_vars_excess_triggered = false;
    let form_loaded_ids = [];
    if ($ajax_forms.length > 0) {
        let load_nex_form = function () {
            let $form_wrap;
            if (form_loaded_ids.length === 0) {
                $form_wrap = $ajax_forms.eq(0);
            } else {
                $form_wrap = $ajax_forms.not('[data-fields-query-id="' + form_loaded_ids.join('"], [data-fields-query-id="') + '"]').eq(0);
            }
            if ($form_wrap.length > 0) {
                form_loaded_ids.push($form_wrap.attr('data-fields-query-id'));
                $form_wrap.addClass('loading').removeClass('preloaded');
                $form_wrap.height($form_wrap.height());
                $.ajax({
                    url: ajaxurl + '?action=hiweb-components-form',
                    type: 'post',
                    dataType: 'json',
                    data: {field_query: $form_wrap.attr('data-fields-query'), scripts_done: hiweb_components_fields_form_scripts_done},
                    async: true,
                    success: function (response) {
                        $form_wrap.removeClass('loading').removeClass('preloaded').addClass('loaded');
                        if (response.hasOwnProperty('success')) {
                            load_form_inputs($form_wrap, response);
                            load_form_scripts(response);
                        }
                        if (response.hasOwnProperty('max_input_vars')) {
                            let test_max_input_vars = parseInt(response.max_input_vars);
                            if (!isNaN(test_max_input_vars) && max_input_vars < test_max_input_vars) max_input_vars = test_max_input_vars;
                            let inputs_length = $('form#post').find('input[name], select[name],textarea[name],file[name]').length;
                            if (inputs_length > max_input_vars && !max_input_vars_excess_triggered) {
                                max_input_vars_excess_triggered = true;
                                notify_max_input_vars_excess(inputs_length, max_input_vars);
                            }
                        }
                        setTimeout(load_nex_form, 50);
                    }
                });
            }
        };
        load_nex_form();
    }


});