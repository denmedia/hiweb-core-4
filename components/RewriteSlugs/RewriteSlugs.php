<?php

namespace hiweb\components\RewriteSlugs;


use WP_Query;


class RewriteSlugs {

    static function init() {
        add_action('init', function() {
            add_field_separator('Rewrite Slugs', __('Rewrite base slug for post type archive page, base custom post and taxonomies.', 'hiweb-core-4'))->tag_label('h1')->location()->options('permalink');
            add_field_tab('Post Types', 'Setup post types archive pages')->location(true);
            foreach (get_post_types([ 'publicly_queryable' => true ]) as $postTypeName) {
                include __DIR__ . '/options-post_type.php';
            }
            add_field_tab('Taxonomies', 'Setup taxonomies archive pages')->location(true);
            foreach (get_taxonomies([ 'public' => true ]) as $taxonomyName) {
                include __DIR__ . '/options-taxonomies.php';
            }
            add_field_tab_end();
        }, 999999);
        ///rewrite rules
        add_filter('register_post_type_args', function($args, $postTypeName) {
            $slugRewrite = self::get_post_type_slug($postTypeName);
            if ($slugRewrite != '') {
                if ( !is_array($args['rewrite'])) $args['rewrite'] = [];
                $args['rewrite']['slug'] = $slugRewrite;
                add_filter('pre_post_link', function($permalink, $post, $leavename) {
                    if (strpos($permalink, '/' . self::get_post_type_slug('post') . '/') !== 0) {
                        $permalink = '/' . self::get_post_type_slug('post') . $permalink;
                    }
                    return $permalink;
                }, 10, 3);
            }
            return $args;
        }, 10, 2);
        add_filter('register_taxonomy_args', function($args, $taxonomy) {
            $slugRewrite = self::get_taxonomy_slug($taxonomy);
            if ($slugRewrite != '') {
                if ( !is_array($args['rewrite'])) $args['rewrite'] = [];
                $args['rewrite']['slug'] = $slugRewrite;
            }
            return $args;
        }, 10, 2);
        ///disable paginate
        add_action('pre_get_posts', function($query) {
            if ( !$query->is_main_query()) return;
            /** @var WP_Query $query */
            if (array_key_exists('post_type', $query->query)) {
                if (self::get_post_type_paginateless($query->query['post_type'])) {
                    self::_disable_paginate($query);
                }
            }
            foreach (get_taxonomies() as $taxonomyName) {
                $taxonomyObject = get_taxonomy($taxonomyName);
                if (is_string($taxonomyObject->query_var) && array_key_exists($taxonomyObject->query_var, $query->query) && self::get_taxonomy_paginateless($taxonomyName)) {
                    self::_disable_paginate($query);
                }
            }
        });
    }


    /**
     * @param WP_Query $query
     */
    static private function _disable_paginate(WP_Query $query) {
        if ($query->is_paged()) {
            unset($query->query['paged']);
            $query->is_paged = false;
            $query->is_archive = false;
            $query->is_tax = false;
            $query->is_paged = false;
            $query->query_vars['paged'] = false;
            $query->set_404();
            header("HTTP/1.0 404 Not Found");
        }
        add_filter('the_posts', function($posts, $query) {
            $query->max_num_pages = false;
            return $posts;
        }, 10, 2);
    }


    /**
     * @param $postTypeName
     * @return string
     */
    static function get_post_type_slug($postTypeName): string {
        return (string)get_field('post_type-' . $postTypeName . '-archive-slug', 'permalink', '');
    }


    /**
     * @param $postTypeName
     * @return bool
     */
    static function get_post_type_paginateless($postTypeName): bool {
        return (bool)get_field('post_type-' . $postTypeName . '-archive-paginateless', 'permalink', false);
    }


    /**
     * @param $taxonomyName
     * @return string
     */
    static function get_taxonomy_slug($taxonomyName): string {
        return (string)get_field('taxonomy-' . $taxonomyName . '-slug', 'permalink');
    }


    /**
     * @param $taxonomyName
     * @return bool
     */
    static function get_taxonomy_paginateless($taxonomyName): bool {
        return (bool)get_field('taxonomy-' . $taxonomyName . '-archive-paginateless', 'permalink', false);
    }

}