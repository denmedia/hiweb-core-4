<?php
/**
 * @var string       $postTypeName
 * @var WP_Post_Type $postTypeObject
 */

use hiweb\components\Structures\StructuresFactory;


$postTypeObject = get_post_type_object($postTypeName);
if ($postTypeObject->has_archive || $postTypeName === 'post') {
    add_field_separator(get_fontawesome('far fa-thumbtack') . ' ' . $postTypeObject->label)->tag_label('h2')->location(true);
    $defaultSlug = '';
    $defaultUrl = false;
    if ($postTypeName === 'post') {
        if (StructuresFactory::get_blog_page() instanceof WP_Post) $defaultUrl = get_url()->get_base() . '/' . StructuresFactory::get_blog_page()->post_name;
    } else {
        $defaultSlug = $postTypeName;
        $defaultUrl .= get_url()->get_base() . '/' . $defaultSlug;
    }
    add_field_text('post_type-' . $postTypeName . '-archive-slug')->placeholder($defaultSlug)->label(__('Rewrite archive page slug', 'hiweb-core-4') . ($defaultUrl !== false ? (' <a href="' . $defaultUrl . '" target="_blank">' . get_fontawesome('fab fa-external-link') . '</a>') : ''))->location(true);
    add_field_checkbox('post_type-' . $postTypeName . '-archive-paginateless')->label_checkbox(__('Disable paginate on archive page'))->description(__('This option is useful if you do not plan to use pagination on the archive page of this post type. For example, ajax loading posts or just displaying subcategories instead of posts.', 'hiweb-core-4'))->location(true);
}