<?php
/**
 * @var string       $postTypeName
 * @var WP_Post_Type $postTypeObject
 * @var string       $taxonomyName
 * @var WP_Taxonomy  $taxonomyObject
 */

$taxonomyObject = get_taxonomy($taxonomyName);
if ($taxonomyObject->public) {
    add_field_separator(get_fontawesome('fal fa-folder-tree') . ' ' . $taxonomyObject->label)->tag_label('h2')->location(true);
    add_field_text('taxonomy-' . $taxonomyName . '-slug')->placeholder($taxonomyName)->label(__('Rewrite archive page slug', 'hiweb-core-4'))->location(true);
    add_field_checkbox('taxonomy-' . $taxonomyName . '-archive-paginateless')->label_checkbox(__('Disable paginate on archive page'))->description(__('This option is useful if you do not plan to use pagination on the archive page of this post type. For example, ajax loading posts or just displaying subcategories instead of posts.', 'hiweb-core-4'))->location(true);
}