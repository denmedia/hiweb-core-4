/**
 * Created by DenMedia on 25.10.2016.
 */

let hiweb_field_file = {

    init: function (root) {
        let $root = jQuery(root);
        if (!$root.is('.hiweb-field-type-file')) {
            $root = $root.closest('.hiweb-field-type-file');
        }
        if ($root.length === 0) return;
        if ($root.is('.hiweb-field-type-file-init')) return;
        $root.addClass('hiweb-field-type-file-init');
        $root.on('click', '[data-click-wp-media]', function (e) {
            e.preventDefault();
            hiweb_field_file.event_click_select(root);
        }).on('click', '[data-click-clear]', function (e) {
            e.preventDefault();
            hiweb_field_file.deselect_file(root);
        });
    },

    event_click_select: function (root) {
        root = jQuery(root);
        let media_options = {
            title: 'Выбор файла',
            multiple: false,
            button: {text: 'Выбрать файл'}
        };
        let gallery_window = wp.media(media_options);
        gallery_window.on('select', function () {
            hiweb_field_file.select_file(root, gallery_window.state().get('selection').first().toJSON());
        });
        gallery_window.open();
    },

    select_file: function (root, selection) {
        let $input = root.find('input[name]');
        let media_id = selection.id;
        let $file_info = root.find('[data-message="file"]');
        $input.val(media_id);
        let file_info = '';
        if (selection.hasOwnProperty('filename')) {
            file_info += selection.filename;
        }
        if (selection.hasOwnProperty('filesizeHumanReadable')) {
            file_info += ', size:' + selection.filesizeHumanReadable;
        }
        $file_info.val(file_info);
        if (selection.hasOwnProperty('editLink')) {
            root.find('[data-click-edit-attachment]').show().attr('href', selection.editLink);
        } else {
            root.find('[data-click-edit-attachment]').hide();
        }
        root.attr('data-has-file', '1');
    },

    edit_file: function (current) {
        var input = current.find('input');
        var media_options = {
            title: 'Обновление файла',
            multiple: false,
            button: {text: 'Обновить файл'}
        };
        if (current.is('.hiweb-field-image')) {
            media_options.library = {type: 'image'};
        }
        var gallery_window = wp.media(media_options);
        gallery_window.on('open', function () {
            var selection = gallery_window.state().get('selection');
            ids = [input.val()];
            ids.forEach(function (id) {
                attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            });
        });
        gallery_window.on('select', function () {
            hiweb_field_file.select_file(current, gallery_window.state().get('selection').first().toJSON());
        });
        gallery_window.open();
    },

    deselect_file: function (root) {
        root = jQuery(root);
        var input = root.find('input[name]').val('');
        var file_preview = root.find('.thumbnail');
        file_preview.css('background-file', 'none');
        root.attr('data-has-file', '0');
        root.attr('data-file-mime', '');
        root.attr('data-file-image', '');
        root.find('[data-message="file"]').val('');
    }

};

jQuery('body').on('hiweb-form-ajax-loaded hiweb-field-repeat-added-row', '.hiweb-components-form-ajax-wrap', function () {
    jQuery(this).find('.hiweb-field-type-file').each(function () {
        hiweb_field_file.init(this)
    });
}).find('.hiweb-field-type-file').each(function () {
    hiweb_field_file.init(this)
});

// jQuery('.hiweb-type-field-file').each(function () {
//     hiweb_field_file.init(this);
// });
//
// jQuery('body').on('hiweb-field-repeat-added-row', '.hiweb-type-field-file input[name]', function () {
//     hiweb_field_file.init(jQuery(this).closest('.hiweb-type-field-file'));
// });