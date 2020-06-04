jQuery(document).ready(function () {

    var hiweb_language_import_step_errors_limit = 5;
    var hiweb_language_import_step_count = 0;
    var hiweb_language_import_step_total = 0;
    var hiweb_language_import_step = function () {
        if (hiweb_language_import_step_errors_limit < 1) {
            alert('Превышено число ошибок во время импорта!');
        } else if (hiweb_language_post_ids.length < 1) {
            $('.jumbotron').html('<h1 class="display-4">Все записи импортированы!</h1>');
        } else {
            let post_id = hiweb_language_post_ids.pop();
            hiweb_language_import_step_count++;
            $.ajax({
                url: 'admin-ajax.php?action=hiweb_theme_languages_multisite_migrate_post',
                type: 'post',
                data: {post_id: post_id, site_id: $('input[name="site_id"]').val()},
                dataType: 'json',
                success: function (response) {
                    if(response.hasOwnProperty('success')) {
                        if(!response.success) {
                            hiweb_language_import_step_errors_limit--;
                        }
                    } else {
                        hiweb_language_import_step_errors_limit--;
                    }
                },
                error: function (response) {
                    console.warn(response);
                    hiweb_language_import_step_errors_limit--;
                },
                complete: function () {
                    let proccess_percent = (hiweb_language_import_step_count / hiweb_language_import_step_total) * 100;
                    $('[data-language-progress] .progress-bar').width(proccess_percent + '%').html(hiweb_language_import_step_count + ' / ' + hiweb_language_import_step_total);
                    hiweb_language_import_step();
                }
            });
        }
    };

    $('body').on('click', '[data-multisites-import]', function (e) {
        e.preventDefault();
        $(this).fadeOut();
        if (!hiweb_language_post_ids) {
            alert('Ошибка чтения массива зщаписей!');
        } else {
            hiweb_language_import_step_total = hiweb_language_post_ids.length;
            hiweb_language_import_step();
        }
    })

});