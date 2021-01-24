let hiweb_field_images = function (e) {
    let $root = jQuery(e.target);
    let $source = $root.find('[data-source-image]');
    let $wrap = $root.find('[data-images-wrap]');
    let count_images = () => {
        let count = $wrap.find('[data-item-image]').length;
        $root.attr('data-images-count', count);
        $root.find('[data-images-count-wrap]').html(count);
        if (count > 24) {
            $root.attr('data-images-count-id', 'many');
        } else if (count > 12) {
            $root.attr('data-images-count-id', 'medium');
        } else {
            $root.attr('data-images-count-id', 'low');
        }
    }
    count_images();
    if (typeof $wrap.sortable === 'function') {
        $wrap.sortable({
            items: '[data-item-image]',
            distance: 5,
            cursor: 'move',
            helper: 'original',
            tolerance: "pointer",
            revert: true,
            forcePlaceholderSize: true,
            connectWith: jQuery('.hiweb-field_images [data-images-wrap]'),
            start: function () {
                $wrap.sortable('option', 'connectWith', jQuery('.hiweb-field_images [data-images-wrap]'));
            },
            update: function () {
                $wrap.find(':input[name]').attr('name', $source.find(':input[name]').attr('name'));
            }
        });
    }
    let shuffleElements = function () {
        let $elements = $wrap.find('[data-item-image]');
        let i, index1, index2, temp_val;

        let count = $elements.length;
        let $parent = $elements.parent();
        let shuffled_array = [];


        // populate array of indexes
        for (i = 0; i < count; i++) {
            shuffled_array.push(i);
        }

        // shuffle indexes
        for (i = 0; i < count; i++) {
            index1 = (Math.random() * count) | 0;
            index2 = (Math.random() * count) | 0;

            temp_val = shuffled_array[index1];
            shuffled_array[index1] = shuffled_array[index2];
            shuffled_array[index2] = temp_val;
        }

        // apply random order to elements
        $elements.detach();
        for (i = 0; i < count; i++) {
            $parent.append($elements.eq(shuffled_array[i]));
        }
    };
    let open_media_select = (add_index, item) => {
        let gallery_window = wp.media({
            title: 'Выбор файла',
            multiple: true,
            button: {text: 'Выбрать файл'},
            library: {type: 'image'}
        });
        let replace = false;
        if (typeof item !== 'undefined') {
            let $input = jQuery(item).find('input[name]');
            if ($input.length > 0) {
                replace = jQuery(item);
                gallery_window.on('open', function () {
                    let selection = gallery_window.state().get('selection');
                    let attachment = wp.media.attachment($input.val());
                    attachment.fetch();
                    selection.add(attachment ? [attachment] : []);
                });
            }
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
                        } else {
                            for (let size_name in selection.sizes) {
                                image_src = selection.sizes[size_name]['url'];
                                break;
                            }
                        }
                    }
                    if (selection.hasOwnProperty('editLink')) {
                        edit_link = selection.editLink;
                    }
                    if (selection.hasOwnProperty('url')) {
                        original_src = selection.url;
                    }
                    ///
                    if (replace === false) {
                        let $new_item_image = $source.clone(true);
                        $new_item_image
                            .removeAttr('data-source-image')
                            .attr('data-item-image', selection.id)
                            .attr('data-attachment-id', selection.id)
                            .css({'background-image': 'url(' + image_src + ')'});
                        $new_item_image.find('[data-link="edit_link"]').attr('href', edit_link);
                        $new_item_image.find('[data-link="url"]').attr('href', original_src);
                        $new_item_image.find('input').val(selection.id);
                        if (add_index < 0) {
                            $wrap.find('[data-image-plus="1"]').before($new_item_image);
                        } else {
                            $wrap.find('[data-image-plus="0"]').after($new_item_image);
                        }
                        count_images();
                    } else {
                        replace.attr('data-item-image', selection.id)
                            .attr('data-attachment-id', selection.id)
                            .css({'background-image': 'url(' + image_src + ')'});
                        replace.find('[data-link="edit_link"]').attr('href', edit_link);
                        replace.find('[data-link="url"]').attr('href', original_src);
                        replace.find('input').val(selection.id);
                    }
                }
            });
            count_images();
        });
        gallery_window.open();
    };
    $root
        .on('click', '[data-click]', function (e) {
            e.preventDefault()
        })
        .on('click', '[data-click="add"]', (e) => {
            let add_index = jQuery(e.target).closest('[data-click]').attr('data-add-index')
            open_media_select(add_index);
        })
        .on('click', '[data-click="revert"]', () => {
            //todo
        })
        .on('click', '[data-click="shuffle"]', () => {
            shuffleElements();
        })
        .on('click', '[data-click="clear"]', () => {
            $wrap.find('[data-item-image]').slideUp(500, () => {
                $wrap.attr('style', '');
                $wrap.find('[data-item-image]').remove();
                count_images();
            });
        })
        .on('click', '[data-click="edit"]', (e) => {
            open_media_select(0, jQuery(e.target).closest('[data-item-image]'));
        })
        .on('click', '[data-click="remove"]', (e) => {
            let $item_image = jQuery(e.target).closest('[data-item-image]');
            $item_image.fadeOut(500, () => {
                $item_image.remove();
                count_images();
            });
        });
    ///
};

jQuery('body').on('field_init', '.hiweb-field_images', hiweb_field_images);


// jQuery('body').on('hiweb-form-ajax-loaded hiweb-field-repeat-added-row', '.hiweb-components-fields-form-ajax-wrap', function () {
//     jQuery(this).find('.hiweb-field-type-images').each(function () {
//         hiweb_feild_type_images(this)
//     });
// }).find('.hiweb-field-type-images').each(function () {
//     hiweb_feild_type_images(this)
// });