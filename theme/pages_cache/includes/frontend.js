let hiweb_pages_cache_event = setInterval(function () {
    if (/loaded|complete/.test(document.readyState)) {
        clearInterval(hiweb_pages_cache_event);
        ///
        var url = new URL(window.location.href);
        url.searchParams.delete('cache-disable');
        window.history.pushState("", "", url.toString());
        ///
        $.ajax({
            url: document.location.href + '?cache-disable',
            success: function (response) {

                response = $('<div/>').append(response);

                ///CSS
                response.find('link[rel="stylesheet"][href]').each(function () {
                    let href = $(this).attr('href');
                    if ($('html').find('link[rel="stylesheet"][href="' + href + '"]').length === 0) {
                        $('head').append($(this)[0].outerHTML);
                    }
                });

                let admin_bar = response.find('#wpadminbar');
                if (admin_bar.length > 0) {
                    $('body').append(admin_bar[0].outerHTML);
                }

            }
        });
        ///
    }
}, 100);