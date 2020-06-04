jQuery(document).ready(function () {

    ///ASIDE DESKTOP
    let $aside_menu = $('aside, .widget_hiweb_theme_widget_menu_collapse').find('.menu, .product-categories');
    if ($aside_menu.length > 0) {
        $aside_menu.find('.menu-item-has-children, .cat-parent').each(function () {
            let $li = $(this);
            let options = $li.closest('ul[data-options]').attr('data-options');
            let icon_collapse = 'fas fa-plus-circle';
            let icon_expand = 'fas fa-minus-circle';
            if (typeof options === 'string') {
                options = JSON.parse(options);
                icon_collapse = options.icon_collapse;
                icon_expand = options.icon_expand;
            }
            let $button_expand = $('<button data-expand="0"><i class="'+icon_expand+'"></i></button>');
            let $button_collapse = $('<button data-expand="1"><i class="'+icon_collapse+'"></i></button>');
            $button_expand.insertAfter($li.find('> .item-link'));
            $button_collapse.insertAfter($button_expand);
            if (!$li.is('.expanded')) {
                $li.addClass('collapsed');
            }
        });

        //EVENTS
        $('aside, .widget_hiweb_theme_widget_menu_collapse').on('click', '.collapsed > button, .expanded > button', function () {
            let $this = $(this).closest('li');
            if ($this.is('.expanded')) {
                $this.removeClass('expanded').find('> .children, > .sub-menu').slideUp(500, function () {
                    $this.addClass('collapsed');
                });
            } else {
                $this.parent().find('> *.expanded > button').trigger('click');

                $this.find('> .children, > .sub-menu').slideDown(500, function () {
                    $this.removeClass('collapsed').addClass('expanded');
                });
            }
        });
    }

});