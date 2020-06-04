jQuery(document).ready(function ($) {


    let $admin_notices = $('#wpbody-content > .wrap > .notice, #wpbody-content > .wrap > .manage-menus, #wpbody-content > .update-nag, #post-body-content > .notice').not('.hidden');
    if (typeof Noty === 'function') {
        let popups = {};
        let popup_ids = [];
        let make_popup = function ($source_notice) {
            if (!($source_notice instanceof jQuery)) {
                $source_notice = $(this);
            }
            let type = 'alert';
            let timeout = false;
            let layout = 'topRight';
            let closeWith = ['button'];
            let container = '#hiweb-components-adminnotices-wrap';
            let allow_close_to_time = true;
            let animation = {
                open: 'animated fadeInDown',
                close: 'animated fadeOutUp'
            };
            if ($source_notice.is('.update-nag')) {
                timeout = 60000;
                closeWith = ['button'];
                layout = 'bottomRight';
                container = false;
                type = 'info';
            }
            if ($source_notice.is('.is-dismissible')) {
                timeout = 60000;
                $source_notice.find('.notice-dismiss, .components-notice__dismiss').remove();
            }
            if ($source_notice.is('#local-storage-notice')) {
                allow_close_to_time = false;
            }
            if ($source_notice.is('.updated') || $source_notice.is('.notice-success') || $source_notice.is('.is-success')) {
                timeout = 5000;
                allow_close_to_time = false;
                type = 'success';
            }
            if ($source_notice.is('.notice-error') || $source_notice.is('.is-error')) {
                timeout = 60000;
                type = 'error';
            }
            if ($source_notice.is('.notice-warning') || $source_notice.is('.is-warning')) {
                timeout = 60000;
                type = 'warning';
            }
            if ($source_notice.is('.notice-info')) {
                type = 'info';
                layout = 'bottomRight';
            }
            if ($source_notice.is('.components-notice')) {
                container = false;

            }
            if (layout === 'bottomRight') {
                container = false;
                animation = {
                    open: 'animated fadeInRight',
                    close: 'animated fadeOutRight'
                };
            }
            let text = $source_notice.html();
            let popup_id = md5(text);
            popups[popup_id] = new Noty({
                text: '',
                type: type,
                timeout: timeout + (750 - (1500 * Math.random())),
                layout: layout,
                closeWith: closeWith,
                container: container,
                theme: 'bootstrap-v3',
                id: popup_id,
                animation: animation,
                callbacks: {
                    onShow: function () {
                        $source_notice.clone(true).removeClass().appendTo('#' + popup_id + ' .noty_body');
                    }
                }
            });
            if (allow_close_to_time) {
                popup_ids.push(popup_id);
            } else {
                popups[popup_id].show();
            }
            return popups[popup_id];
        };
        $admin_notices.each(make_popup);
        $.ajax({
            url: '/wp-json/hiweb/components/adminnotices/is_show',
            data: {ids: popup_ids},
            type: 'get',
            success: function (response) {
                if (response.hasOwnProperty('popups')) {
                    for (let id in response.popups) {
                        if (response.popups[id]) {
                            popups[id].show();
                            $('#' + id + ' .noty_close_button').on('click', function () {
                                $.ajax({
                                    url: '/wp-json/hiweb/components/adminnotices/force_close',
                                    data: {id: id},
                                    type: 'get'
                                });
                            });
                        }
                    }
                } else {
                    $('#hiweb-components-adminnotices-inline-styles').remove();
                }
            }
        });

        $('.editor-notices').on('DOMNodeInserted', function (e) {
            if (e.target.classList.contains('notice')) {
                make_popup($(e.target)).show();
            }
        });

        $('#editor').on('DOMNodeInserted', function () {
            let $notices = $(this).find('.components-notice-list > .components-notice, .components-notice-list > .components-notice');
            $notices.each(function () {
                make_popup($(this)).show();
            });
        });
    }

});