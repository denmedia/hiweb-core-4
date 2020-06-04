jQuery(document).ready(function ($) {

    $('form').each(function () {
        let $form = $(this);
        let $recaptcha_input = $form.find('[data-hiweb-form-recaptcha-input]');
        if ($recaptcha_input.length > 0 && typeof grecaptcha != 'undefined') {
            let $submit_button = $form.find('input[type="submit"], button[type="submit"]');
            $submit_button.on('click', function (e) {
                if (!$submit_button.is('[hiweb-recaptcha-received]')) {
                    e.preventDefault();
                    $submit_button.attr('disabled', 'disabled');
                    grecaptcha.ready(() => {
                        grecaptcha.execute($recaptcha_input.attr('data-key')).then(function (token) {
                            $submit_button.attr('hiweb-recaptcha-received', 'true');
                            $submit_button.removeAttr('disabled');
                            $recaptcha_input.val(token);
                            $submit_button.trigger('click');
                            $submit_button.removeAttr('hiweb-recaptcha-received');
                        });
                    });
                }
            });
        } else if (typeof grecaptcha === 'undefined') {
            console.error('Объект [grecaptcha] не подключен в теле сайта. Подключите удаленный JS файл [https://www.google.com/recaptcha/api.js?render={recaptcha public key}]');
        }
    });


});