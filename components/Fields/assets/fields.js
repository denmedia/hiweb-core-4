jQuery(document).ready(function ($) {


    let $ajax_forms = $('.hiweb-components-fields-form-ajax-wrap[data-fields-query][data-fields-query-id]');
    let $forms = $('.hiweb-components-fields-form-wrap');
    let max_input_vars = 0;
    let max_input_vars_excess_triggered = false;
    let loaded_scripts = [];
    let loaded_stylesheets = [];
    let form_loading_ids = [];
    let form_loaded_ids = [];
    let fields_input_change = false;

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

    let loadStyleSheet = function (path, fn, scope) {
        let head = document.getElementsByTagName('head')[0], // reference to document.head for appending/ removing link nodes
            link = document.createElement('link');           // create the link node
        link.setAttribute('href', path);
        link.setAttribute('rel', 'stylesheet');
        link.setAttribute('type', 'text/css');

        let sheet, cssRules;
// get the correct properties to check for depending on the browser
        if ('sheet' in link) {
            sheet = 'sheet';
            cssRules = 'cssRules';
        } else {
            sheet = 'styleSheet';
            cssRules = 'rules';
        }

        let interval_id = setInterval(function () {                     // start checking whether the style sheet has successfully loaded
                try {
                    if (link[sheet] && link[sheet][cssRules].length) { // SUCCESS! our style sheet has loaded
                        clearInterval(interval_id);                      // clear the counters
                        clearTimeout(timeout_id);
                        fn.call(scope || window, true, link);           // fire the callback with success == true
                    }
                } catch (e) {
                } finally {
                }
            }, 10),                                                   // how often to check if the stylesheet is loaded
            timeout_id = setTimeout(function () {       // start counting down till fail
                clearInterval(interval_id);             // clear the counters
                clearTimeout(timeout_id);
                head.removeChild(link);                // since the style sheet didn't load, remove the link node from the DOM
                fn.call(scope || window, false, link); // fire the callback with success == false
            }, 15000);                                 // how long to wait before failing

        head.appendChild(link);  // insert the link node into the DOM and start loading the style sheet

        return link; // return the link node;
    };

    let load_form_scripts = function ($form_wrap, response, callback) {
        let javascripts_load_finish = false;
        let styles_load_finish = false;
        let callback_triggered = false;
        ///LOAD JAVASCRIPTS
        let javascripts = [];
        for (let handle in response.js) {
            if (loaded_scripts.indexOf(handle) == -1) {
                javascripts.push([handle, response.js[handle]]);
                loaded_scripts.push(handle);
            }
        }
        let load_next_javascript = () => {
            if (javascripts.length > 0) {
                let script = javascripts.shift();
                let timeoutId;
                timeoutId = window.setTimeout(function () {
                    load_next_javascript();
                }, 1000);
                $.getScript(script[1], () => {
                    hiweb_components_fields_form_scripts_done.push(script[0]);
                    load_next_javascript();
                    clearTimeout(timeoutId);
                });
            } else {
                ///LOAD EXTRAS
                let $js_extra_container = $('<script/>');
                for (let i in response.js_extra) {
                    $js_extra_container.append(response.js_extra[i]);
                }
                $('body').append($js_extra_container);
                javascripts_load_finish = true;
                if (styles_load_finish && javascripts_load_finish && !callback_triggered) {
                    callback_triggered = true;
                    if (typeof callback === 'function') callback();
                }
            }
        };
        load_next_javascript();
        ///LOAD STYLES
        let styles = [];
        for (let handle in response.css) {
            styles.push([handle, response.css[handle]]);
        }
        let load_next_style = () => {
            if (styles.length > 0) {
                let style = styles.shift();
                if ($('link[data-handle="' + style[0] + '"]').length === 0) {
                    loadStyleSheet(style[1], () => {
                        loaded_stylesheets.push(style[0]);
                        load_next_style();
                    });
                } else {
                    load_next_style();
                }
            } else {
                styles_load_finish = true;
                if (styles_load_finish && javascripts_load_finish && !callback_triggered) {
                    callback_triggered = true;
                    if (typeof callback === 'function') callback();
                }
            }
        };
        load_next_style();
    };

    let load_form_inputs = function ($form_wrap, form_html) {
        let new_inputs = $(form_html);
        let $form_inner = $form_wrap.find('.hiweb-components-form-ajax-inner');
        $form_inner.append(new_inputs);
        //new_inputs.find(':input[name]').trigger('hiweb-form-ajax-input-loaded').trigger('hiweb-form-updated');
        new_inputs.on('change keydown', ':input', () => {
            fields_input_change = true;
        });
    };

    let form_auto_height = function ($form_wrap, callback) {
        let $form_inner = $form_wrap.find('.hiweb-components-form-ajax-inner');
        $form_wrap.css({'overflow': 'hidden'}).animate({height: $form_inner.outerHeight()}, 500, () => {
            if (typeof callback === 'function') callback();
            $form_wrap.css({'overflow': 'inherit', 'height': 'auto'});
        });
    };

    let load_next_form = function () {
        let $form_wrap;
        if (form_loading_ids.length === 0) {
            $form_wrap = $ajax_forms.eq(0);
        } else {
            $form_wrap = $ajax_forms.not('[data-fields-query-id="' + form_loading_ids.join('"], [data-fields-query-id="') + '"]').eq(0);
        }
        ///
        let forms = {};
        let $forms = $('.hiweb-components-fields-form-ajax-wrap[data-fields-query][data-fields-query-id][data-form-loaded="0"]');
        $forms.each(function () {
            let $form = $(this);
            forms[$form.attr('data-fields-query-id')] = {query: $form.attr('data-fields-query'), options: $form.attr('data-form-options')};
            form_loading_ids.push($form.attr('data-fields-query-id'));
            $form.height($form.height());
            $form.attr('data-form-loaded', '1');
        });
        ///
        if ($forms.length > 0) {
            $forms.addClass('loading').removeClass('preloaded');
            $.ajax({
                url: ajaxurl + '?action=hiweb-components-form',
                type: 'post',
                dataType: 'json',
                data: {forms: forms, scripts_done: hiweb_components_fields_form_scripts_done},
                async: true,
                success: function (response) {
                    if (response.hasOwnProperty('success')) {
                        load_form_scripts($form_wrap, response, () => {
                            // load_form_inputs($form_wrap, response);
                            // form_auto_height($form_wrap, () => {
                            //     $form_wrap.removeClass('loading').removeClass('loaded');
                            //     $form_wrap.trigger('hiweb-form-ajax-loaded');
                            //     init_inputs();
                            // });
                            // $form_wrap.removeClass('preloading');
                            $forms.each(function () {
                                let form_id = $(this).attr('data-fields-query-id');
                                let $form_wrap = $(this);
                                if (response.forms_html.hasOwnProperty(form_id)) {
                                    load_form_inputs($form_wrap, response.forms_html[form_id]);
                                    form_auto_height($form_wrap, () => {
                                        $form_wrap.removeClass('loading').removeClass('loaded');
                                        $form_wrap.trigger('hiweb-form-ajax-loaded');
                                        init_inputs($form_wrap);
                                    });
                                }
                            });
                            $forms.removeClass('preloading');
                        });
                    }
                    if (response.hasOwnProperty('max_input_vars')) {
                        let test_max_input_vars = parseInt(response.max_input_vars);
                        if (!isNaN(test_max_input_vars) && max_input_vars < test_max_input_vars) max_input_vars = test_max_input_vars;
                        let inputs_length = $('form#post').find('input[name], select[name],textarea[name]').length;
                        if (inputs_length > max_input_vars && !max_input_vars_excess_triggered) {
                            max_input_vars_excess_triggered = true;
                            notify_max_input_vars_excess(inputs_length, max_input_vars);
                        }
                    }
                    setTimeout(load_next_form, 50);
                },
                complete: function () {
                    $forms.each(function () {
                        let $form_wrap = $(this);
                        form_loaded_ids.push($form_wrap.attr('data-fields-query-id'));
                    });
                    ///unlock submit button
                    if ($ajax_forms.length <= form_loaded_ids.length) {
                        $ajax_forms.closest('form').each(function () {
                            $(this).find('button[disabled][data-hiweb-form-submit-stop="1"], input[type="submit"][disabled][data-hiweb-form-submit-stop="1"]').removeAttr('disabled').attr('data-hiweb-form-submit-stop', '0');
                        });
                    }
                }
            });
        }
    };

    let tab_click_handle = function (e) {
        e.preventDefault();
        let $this = $(this);
        if ($this.is('[data-tab-active="0"]')) {
            $this.siblings().attr('data-tab-active', '0');
            $this.attr('data-tab-active', '1');
            $this.closest('.hiweb-fields-form-tabs-wrap').find('[data-tab-content]').slideUp();
            $this.closest('.hiweb-fields-form-tabs-wrap').find('[data-tab-content="' + $this.attr('data-tab-handle') + '"]').slideDown();
        }
    }

    let init_inputs = function ($form_wrap) {
        $form_wrap.find('[data-field-init="0"]').each(function () {
            $(this).trigger('field_init').attr('data-field-init', '1');
        });
        show_hide_inputs($form_wrap);
    }

    let make_qtips = function () {
        let $tooltip_help = $('[data-hiweb-fields-tooltip-help]');
        if ($tooltip_help.length > 0 && typeof $tooltip_help.qtip === 'function') {
            $tooltip_help.each(function () {
                let $source = $(this);
                $source.qtip({
                    content: {
                        text: $source.attr('data-hiweb-fields-tooltip-help')
                    },
                    hide: {
                        //event: 'unfocus click mouseclick',
                        delay: 1000,
                        effect: function (offset) {
                            jQuery(this).fadeOut(400); // "this" refers to the tooltip
                        }
                    },
                    style: {
                        classes: 'qtip-light qtip-shadow'
                    },
                    position: {
                        target: $source,
                        my: 'bottom center',
                        at: 'top center',
                        viewport: true,
                        adjust: {
                            method: 'shift flip'
                        }
                    }
                });
            })
        }
        $.fn.qtip.zindex = 100000;
    }

    let show_hide_inputs = function ($form) {
        let $rule_inputs = $form.find('[data-field-input_name][data-field-show_if], [data-field-input_name][data-field-hide_if]');
        if ($rule_inputs.length === 0) return;
        ///
        setTimeout(() => {
            show_hide_inputs($form)
        }, 1105);
        $rule_inputs.each(function () {
            let $current_input = $(this);
            let rules;
            let showIf = true;
            if ($current_input.is('[data-field-show_if]')) {
                rules = JSON.parse($current_input.attr('data-field-show_if'));
            } else if ($current_input.is('[data-field-hide_if]')) {
                rules = JSON.parse($current_input.attr('data-field-hide_if'));
                showIf = false;
            }
            let math = true;
            if (typeof rules === 'undefined') return null;
            for (let fieldId in rules) {
                let rule = rules[fieldId];
                let $source_field_wrap = $form.find('[data-field-init="1"][data-field-input_name][data-field-id="' + fieldId + '"]');
                let $input = $source_field_wrap.find(':input[name="' + $source_field_wrap.attr('data-field-input_name') + '"]');
                if ($input.length > 0) {
                    if (typeof rule === 'object') {
                        for (let i in rule) {
                            if (rule[i] === $input.val() || ($input.prop('checked') === rule[i])) {
                                math = math && true;
                                break;
                            }
                        }
                    } else if ($input.val() === rule || ($input.prop('checked') === rule)) {
                        math = math && true;
                    } else {
                        math = false;
                    }
                }
            }
            if (math) {
                if (showIf) {
                    $current_input.closest('.hiweb-fieldset').removeClass('hiweb-fieldset-disabled');
                } else {
                    $current_input.closest('.hiweb-fieldset').removeClass('hiweb-fieldset-disabled');
                }
            } else {
                if (!showIf) {
                    $current_input.closest('.hiweb-fieldset').removeClass('hiweb-fieldset-disabled');
                } else {
                    $current_input.closest('.hiweb-fieldset').removeClass('hiweb-fieldset-disabled');
                }
            }
            if ((showIf && math === true) || (!showIf && math === false)) {
                $current_input.closest('.hiweb-fieldset').removeClass('hiweb-fieldset-disabled');
            } else {
                $current_input.closest('.hiweb-fieldset').addClass('hiweb-fieldset-disabled');
            }
        });
    }

    ///AJAX FORM
    if ($ajax_forms.length > 0) {
        ///disable submit form
        $ajax_forms.closest('form').each(function () {
            $(this).find('button, input[type="submit"]').not('[disabled], [data-hiweb-form-submit-stop="1"]').attr('disabled', '').attr('data-hiweb-form-submit-stop', '1');
        });
        ///
        load_next_form();
    }

    $('form').on('submit', function () {
        fields_input_change = false;
    });
    $(window).on('beforeunload', function () {
        if (fields_input_change) {
            return wp.i18n.__('The changes you made will be lost if you navigate away from this page.');
        }
        return undefined;
    });

    ///TABS
    $forms.on('click', '.hiweb-fields-form-tabs-handles [data-tab-handle]', tab_click_handle);

    ///HELP TOOLTIP
    $forms.on('hiweb-form-ajax-loaded', make_qtips);

    init_inputs($forms);
    setInterval(() => {
        load_next_form();
    }, 1000);
});