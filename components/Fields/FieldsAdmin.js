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

    let loadStyleSheet = function (path, fn, scope) {
        var head = document.getElementsByTagName('head')[0], // reference to document.head for appending/ removing link nodes
            link = document.createElement('link');           // create the link node
        link.setAttribute('href', path);
        link.setAttribute('rel', 'stylesheet');
        link.setAttribute('type', 'text/css');

        var sheet, cssRules;
// get the correct properties to check for depending on the browser
        if ('sheet' in link) {
            sheet = 'sheet';
            cssRules = 'cssRules';
        } else {
            sheet = 'styleSheet';
            cssRules = 'rules';
        }

        var interval_id = setInterval(function () {                     // start checking whether the style sheet has successfully loaded
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
            javascripts.push([handle, response.js[handle]]);
        }
        let load_next_javascript = () => {
            if (javascripts.length > 0) {
                let script = javascripts.shift();
                let timeoutId;
                timeoutId = window.setTimeout(function() {
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

    let load_form_inputs = function ($form_wrap, response) {
        let new_inputs = $(response.form_html);
        let $form_inner = $form_wrap.find('.hiweb-components-form-ajax-inner');
        $form_inner.append(new_inputs);
        new_inputs.find('input[name], select[name],textarea[name],file[name]').trigger('hiweb-form-ajax-input-loaded').trigger('hiweb-form-updated');
    };

    let form_auto_height = function ($form_wrap, callback) {
        let $form_inner = $form_wrap.find('.hiweb-components-form-ajax-inner');
        $form_wrap.css({'overflow': 'hidden'}).animate({height: $form_inner.outerHeight()}, 500, () => {
            if (typeof callback === 'function') callback();
            $form_wrap.css({'overflow': 'inherit', 'height': 'auto'});
        });
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
                        if (response.hasOwnProperty('success')) {
                            load_form_scripts($form_wrap, response, () => {
                                load_form_inputs($form_wrap, response);
                                form_auto_height($form_wrap, () => {
                                    $form_wrap.removeClass('loading').removeClass('loaded');
                                    $form_wrap.trigger('hiweb-form-ajax-loaded');
                                });
                                $form_wrap.removeClass('preloading');
                            });
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