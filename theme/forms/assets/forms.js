var hiweb_theme_widget_forms = {

    form_root_seletor: '.hiweb-theme-widget-form',
    wrap_root_selector: '',
    status_root_selector: '.hiweb-theme-widget-form-status',
    recaptcha_local_selector: 'input[name="recaptcha-token"]',

    init: function () {
        var $form_widgets = $(hiweb_theme_widget_forms.form_root_seletor);
        $form_widgets.each(function () {
            $(this).find('button[type="submit"]').removeAttr('disabled');
            hiweb_theme_widget_forms.make($(this));
        });
        //Fancybox values setup
        $('body').on('click', 'a[href][data-widget-form-modal-open]', function () {
            let $form = $($(this).attr('href'));
            if ($form.length > 0) {
                ///set values
                let data = JSON.parse($(this).attr('data-values'));
                for (let name in data) {
                    if (typeof data[name] == 'object') {
                        $form.find('[name="' + name + '"]').val(JSON.stringify(data[name]));
                    } else {
                        $form.find('[name="' + name + '"]').val(data[name]);
                    }
                }
                ///ajax input reload
                $form.find('[data-ajax-html]').each(function () {
                    let $input_wrap = $(this);
                    $input_wrap.css({opacity: 0});
                    $.ajax({
                        url: '/wp-json/hiweb_theme/forms/input_html',
                        type: 'post',
                        dataType: 'json',
                        data: {form_id: $input_wrap.closest('[data-form-id]').attr('data-form-id'), data: $input_wrap.find('[name]').serializeArray()},
                        success: function (response) {
                            if (response.success) {
                                $input_wrap.fadeIn(500);
                                $input_wrap.replaceWith(response.html);
                            } else {

                            }
                        }
                    });
                });
            }
            $('body').trigger('hiweb-theme-form-modal-open', $(this));
        });
        //Path for remake form without events!
        $('body').on('submit', hiweb_theme_widget_forms.form_root_seletor, function (e) {
            if (typeof $._data($(this).get(0), 'events') === 'undefined') {
                e.preventDefault();
                hiweb_theme_widget_forms.make($(this));
                $(this).trigger('submit');
            }
        });
    },

    make: function ($form) {
        //Vars
        // var has_recaptcha = $form.find('input[name="recaptcha-token"]').length > 0;
        ///EVENTS
        //Require Reset
        $form.on('keyup change', '[name]', function () {
            $(this).removeClass('require-error').parents('.require-error').removeClass('require-error');
            //$(this).removeClass('require-error').parent().removeClass('require-error').parent().removeClass('require-error');
        });
        $form.on('submit', function (e) {
            $(this).removeClass('require-error').parent().removeClass('require-error').parent().removeClass('require-error');
            e.preventDefault(); // prevent native submit
            let validate = true;
            ///checkboxes validate
            if ($(this).find('[data-form-field-checkboxes-min]').length > 0) {
                $(this).find('[data-form-field-checkboxes-min]').each(function () {
                    let $checkboxes = $(this);
                    let min_checked = parseInt($checkboxes.attr('data-form-field-checkboxes-min'));
                    if (isNaN(min_checked)) min_checked = 0;
                    if ($checkboxes.find('input[type="checkbox"]:checked').length < min_checked) {
                        validate = false;
                        $checkboxes.find('input[type="checkbox"]').addClass('require-error').closest('.input-wrap').addClass('require-error');
                    }
                });
            }
            ///text check
            if ($(this).find('input[type="text"][data-required]').length > 0) {
                $(this).find('input[type="text"][data-required]').each(function () {
                    if ($(this).val() === '') {
                        validate = false;
                        $(this).addClass('require-error').closest('.input-wrap').addClass('require-error');
                    }
                });
            }
            ///textarea check
            if ($(this).find('textarea[data-required]').length > 0) {
                $(this).find('textarea[data-required]').each(function () {
                    if ($(this).val() === '') {
                        validate = false;
                        $(this).addClass('require-error').closest('.input-wrap').addClass('require-error');
                    }
                });
            }
            ///email check
            if ($(this).find('input[type="email"][data-required]').length > 0) {
                $(this).find('input[type="email"][data-required]').each(function () {
                    if ($(this).val() === '') {
                        validate = false;
                        $(this).addClass('require-error').closest('.input-wrap').addClass('require-error');
                    }
                    // var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    // if (!re.test(String($(this)).toLowerCase())) {
                    //     validate = false;
                    //     $(this).addClass('require-error').closest('.input-wrap').addClass('require-error');
                    // }
                });
            }
            ///checkbox check
            if ($(this).find('input[type="checkbox"][data-required]').length > 0) {
                $(this).find('input[type="checkbox"][data-required]').each(function () {
                    if (!$(this).prop('checked')) {
                        validate = false;
                        $(this).addClass('require-error').closest('.input-wrap').addClass('require-error');
                    }
                });
            }
            ///
            if (validate) {
                hiweb_theme_widget_forms.set_status($form, 'wait');
                hiweb_theme_widget_forms.submit($form);
                // if (has_recaptcha) {
                //     hiweb_theme_widget_forms.recapthca_get_token($form, function () {
                //         hiweb_theme_widget_forms.submit($form);
                //     });
                // } else {
                //     hiweb_theme_widget_forms.submit($form);
                // }
            }
        });
        $form.on('click tap', '[data-form-status-close]', function (e) {
            e.preventDefault();
            hiweb_theme_widget_forms.set_status($form, '');
        });
        ///MASK INPUTS
        let $form_mask_inputs = $form.find('[data-input-mask]');
        if ($form_mask_inputs.length > 0 && typeof $form_mask_inputs.mask === 'function') {
            $form_mask_inputs.each(function () {
                $(this).mask($(this).attr('data-input-mask'));
            });
        }

    },

    // recapthca_get_token: function ($form, success_fn) {
    //     if (typeof grecaptcha === 'undefined') {
    //         console.error('Объект [grecaptcha] не подключен в теле сайта. Подключите удаленный JS файл [https://www.google.com/recaptcha/api.js?render={recaptcha public key}]');
    //     } else {
    //         var $input_token = $form.find(hiweb_theme_widget_forms.recaptcha_local_selector);
    //         if ($input_token.length > 0) {
    //             grecaptcha.execute($input_token.data('key')).then(function (token) {
    //                 $input_token.val(token);
    //                 if (typeof success_fn === 'function') success_fn($form, $input_token);
    //                 //$input_token.closest('form').find('[type="submit"]').removeAttr('disabled');
    //             });
    //         }
    //     }
    // },

    set_status: function ($form, $status, message) {
        //default status message set
        var $form_status = $form.find(hiweb_theme_widget_forms.status_root_selector);
        if (!$form_status.is('[data-wait-message]')) {
            $form_status.attr('data-wait-message', $form_status.find('.message').html());
        } else {
            $form_status.find('.message').html($form_status.data('wait-message'));
        }
        //
        //Set status and message
        if (!$status) $status = '';
        $form.attr('data-status', $status);
        if (message) $form_status.find('.message').html(message);
    },

    submit: function ($form) {
        $form.ajaxSubmit({
            dataType: 'json',
            success: function (response) {
                if (!response.hasOwnProperty('success')) {
                    hiweb_theme_widget_forms.set_status($form, 'error', 'Сервер вернул не верные данные');
                } else {
                    hiweb_theme_widget_forms.set_status($form, response.status, response.message);
                    if (response.status === 'success') {
                        $form.trigger('reset');
                        setTimeout(function () {
                            $.fancybox.close(true);
                            hiweb_theme_widget_forms.set_status($form, '');
                        }, 3000);
                    } else {
                        setTimeout(function () {
                            if ($form.data('status') === 'error' || $form.data('status') === 'warn') {
                                hiweb_theme_widget_forms.set_status($form, '');
                            }
                        }, 5000);
                    }

                    if (response.hasOwnProperty('error_inputs')) {
                        for (var index in response.error_inputs) {
                            var name = response.error_inputs[index];
                            $form.find('[name="' + name + '"]').addClass('require-error').parent().addClass('require-error').closest('.input-wrap').addClass('require-error');
                            hiweb_theme_widget_forms.set_status($form, '');
                        }
                    }

                    if (response.hasOwnProperty('callback_js') && response.callback_js !== '') {
                        eval(response.callback_js);
                    }

                    if (response.hasOwnProperty('html') && response.html !== '') {
                        jQuery('body').append(response.html);
                    }
                }
            },
            error: function (response) {
                console.error(response);
                hiweb_theme_widget_forms.set_status($form, 'error', 'Неизвестная ошибка, попробуйте снова');
            }
        });
    }

};

jQuery(document).ready(hiweb_theme_widget_forms.init);