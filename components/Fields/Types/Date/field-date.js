jQuery(document).ready(function ($) {

    var hiweb_field_date = {

        selector_root: '.hiweb-field-type-date',

        init: function () {
            $(hiweb_field_date.selector_root).each(function () {
                hiweb_field_date.make(this);
            });
        },

        make: function (root) {
            jQuery('body').on('click', hiweb_field_date.selector_root + ' .ui.icon.button', function (e) {
                e.preventDefault();
            });
            // $(root).find('[data-datepicker-show]')
            //     .popup({
            //         inline: false,
            //         hoverable: true,
            //         position: 'bottom right',
            //         popup: jQuery('.custom.popup'),
            //         on: 'click',
            //         delay: {
            //             show: 300,
            //             hide: 800
            //         }
            //     });
            $(root).find('[data-calendarpicker="1"]').zabuto_calendar({
                language: "ru",
                cell_border: false,
                nav_icon: {
                    prev: '<i class="angle left icon"></i>',
                    next: '<i class="angle right icon"></i>'
                },
                action: function () {
                    $(root).find('input[name]').val($(this).data("date"));
                }
            }).attr('data-calendarpicker', '0');
        }

    };

    hiweb_field_date.init();


});