jQuery(document).ready(function ($) {

    let hiweb_field_date = {

        selector_root: '.hiweb-field_date',

        init: function () {
            $(this).each(function () {
                let $root = $(this);
                let $input = $root.find('input[name]');
                ///MAKE CALENDAR
                let $calendar = $root.find('[data-calendarpicker]');
                if (typeof $calendar.zabuto_calendar === 'function') {
                    ///do nothing
                } else {
                    console.error('$calendar.zabuto_calendar is not defined....');
                }
                ///MAKE QTIP DROPDOWN
                let $button_picker = $root.find('[data-datepicker-show]');
                if (typeof $button_picker.qtip === 'function') {
                    let $qtip = $button_picker.on('click', function (e) {
                        e.preventDefault();
                        if ($calendar.is('[data-calendarpicker="0"]')) {
                            $calendar.zabuto_calendar({
                                language: "ru",
                                cell_border: false,
                                today: true,
                                data: $input.val() !== '' ? {"date": $input.val(), "badge": false} : {},
                                nav_icon: {
                                    prev: $calendar.find('[data-calendarpicker-arrow="left"]').html(),
                                    next: $calendar.find('[data-calendarpicker-arrow="right"]').html(),
                                },
                                action: function (e) {
                                    $calendar.find('div.zabuto_calendar .table td.event').removeClass('event');
                                    jQuery(e.target).closest('td').addClass('event');
                                    $root.find('input[name]').val($(this).data("date"));
                                    setTimeout(() => {
                                        $qtip.qtip('hide');
                                    }, 500);
                                }
                            }).attr('data-calendarpicker', '1');
                            $calendar.find('[data-calendarpicker-arrow]').remove();
                        }
                    }).qtip({
                        content: $calendar,
                        show: {
                            event: 'click'
                        },
                        hide: {
                            event: 'unfocus click mouseclick'
                        },
                        style: {
                            classes: 'qtip-light qtip-shadow qtip-hiweb-fields'
                        },
                        position: {
                            target: $input,
                            my: 'top center',
                            at: 'bottom center',
                            adjust: {
                                method: 'shift none'
                            }
                        }
                    });
                } else {
                    console.error('$button_picker.qtip is not defined');
                }
            });
        }
    };
    $('body').on('field_init', hiweb_field_date.selector_root, hiweb_field_date.init);


});