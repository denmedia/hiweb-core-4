let hiweb_field_type_image = {

    init: function (root) {
        let $root = jQuery(root.target);
        if ($root.length === 0) return;
        $root.on('click', '[data-click]', function (e) {
            e.preventDefault();
            let $button = jQuery(this);
            switch ($button.attr('data-click')) {
                case 'select':
                    hiweb_field_type_image.click_wp_media($root);
                    break;
                case 'remove':
                    $root.find('[data-image-place]').css({'background-image': ''});
                    $root
                        .attr('data-has-file', '0')
                        .find('input[name]').val('');
                    break;
                case 'edit':
                    hiweb_field_type_image.click_wp_media($root);
                    break;
            }
        });
    },

    click_wp_media: function ($root) {
        let gallery_window = wp.media({
            title: 'Выбор файла',
            multiple: false,
            button: {text: 'Выбрать файл'},
            library: {type: 'image'}
        });
        let $input = $root.find('input[name]');
        if ($input.val() != '') {
            gallery_window.on('open', function () {
                var selection = gallery_window.state().get('selection');
                attachment = wp.media.attachment($input.val());
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            });
        }
        gallery_window.on('select', function () {
            gallery_window.state().get('selection').forEach(function (item, index) {
                let selection = item.toJSON();
                if (selection.hasOwnProperty('id')) {
                    let image_src = '';
                    let edit_link = '';
                    let original_src = '';
                    if (selection.hasOwnProperty('icon')) {
                        image_src = selection.icon;
                    }
                    if (selection.hasOwnProperty('sizes')) {
                        if (selection.sizes.hasOwnProperty('medium')) {
                            image_src = selection.sizes['medium']['url'];
                        } else if (selection.sizes.hasOwnProperty('thumbnail')) {
                            image_src = selection.sizes['thumbnail']['url'];
                        }
                        for (let size_name in selection.sizes) {
                            if (selection.sizes[size_name]['width'] >= $root.width()) {
                                image_src = selection.sizes[size_name]['url'];
                                break;
                            }
                        }
                    }
                    if (selection.icon === image_src) {
                        for (let size_name in selection.sizes) {
                            image_src = selection.sizes[size_name]['url'];
                            break;
                        }
                    }
                    if (selection.hasOwnProperty('editLink')) {
                        edit_link = selection.editLink;
                    }
                    if (selection.hasOwnProperty('url')) {
                        original_src = selection.url;
                    }
                    $root.find('[data-image-place]').css({'background-image': 'url(' + image_src + ')'});
                    $root
                        .attr('data-file-mime', selection.mime)
                        .attr('data-has-file', '1')
                        .attr('data-attachment-id', selection.id);
                    $root.find('[data-link="edit_link"]').attr('href', edit_link);
                    $root.find('[data-link="url"]').attr('href', original_src);
                    $root.find('input').val(selection.id);
                }
            });
        });
        gallery_window.open();
    }

}

jQuery('body').on('field_init', '.hiweb-field_image[data-field-init="0"]', hiweb_field_type_image.init);

// jQuery('body').on('hiweb-form-ajax-loaded hiweb-field-repeat-added-row', '.hiweb-components-fields-form-ajax-wrap', function () {
//     jQuery(this).find('.hiweb-field-type-image').each(function () {
//         hiweb_field_type_image.init(this)
//     });
// }).find('.hiweb-field-type-image').each(function () {
//     hiweb_field_type_image.init(this)
// });