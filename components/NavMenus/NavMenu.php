<?php

namespace hiweb\components\NavMenus;


use hiweb\components\Structures\StructuresFactory;
use hiweb\core\Cache\CacheFactory;
use stdClass;
use WP_Post;
use WP_Term;


/**
 * Class NavMenu
 * @package hiweb\components\NavMenus
 * @version 1.2
 */
class NavMenu {


    private $id;
    private $wp_term;
    private $loaded = false;
    private $items = [];
    private $items_hierarchy = [];


    public function __construct($nav_menu_id) {
        $this->id = intval($nav_menu_id);
        $this->_load();
    }


    /**
     * Load all menu items to wp cache
     * @version 1.1
     */
    public function _load() {
        if ($this->loaded) return;
        $this->loaded = true;
        global $wpdb;
        $query = [];
        $query[] = "SELECT * FROM {$wpdb->posts} as navposts";
        $query[] = "JOIN {$wpdb->term_relationships} as tr ON tr.term_taxonomy_id='{$this->id}' AND tr.object_id=navposts.ID";
        //$query[] = "JOIN {$}"
        $query[] = "WHERE navposts.post_type='nav_menu_item' AND navposts.post_status='publish'";
        $query[] = "ORDER BY navposts.menu_order";
        $wpdb->get_results(join("\n", $query));
        $found_post_ids = [];
        if ($wpdb->last_result && is_array($wpdb->last_result)) {
            ///collect found items
            foreach ($wpdb->last_result as $item) {
                $found_post_ids[] = $item->ID;
                $item->title = $item->post_title;
                $this->items[$item->ID] = $item;
            }
            /// preload post meta
            $query = [];
            $query[] = "SELECT posts.ID, meta.meta_key, meta.meta_value FROM {$wpdb->posts} AS posts";
            $query[] = "LEFT JOIN {$wpdb->postmeta} AS meta ON meta.post_id=posts.ID";
            $query[] = 'WHERE posts.ID IN (' . join(',', $found_post_ids) . ')';
            $wpdb->get_results(join("\n", $query));
            ///collect items meta data
            if ($wpdb->last_result && is_array($wpdb->last_result)) {
                foreach ($wpdb->last_result as $item) {
                    if (strpos($item->meta_key, '_menu_item_') === 0 && array_key_exists($item->ID, $this->items)) {
                        $this->items[$item->ID]->{str_replace('_menu_item_', '', $item->meta_key)} = $item->meta_value;
                    }
                    $current_meta = wp_cache_get( $item->ID, 'post_meta' );
                    if( !is_array( $current_meta ) ) $current_meta = [];
                    $current_meta[ $item->meta_key ] = [ $item->meta_value ];
                    wp_cache_set( $item->ID, $current_meta, 'post_meta' );
                }
            }
            ///complete item process
            foreach ($this->items as $id => $item) {
                switch($item->type) {
                    case 'post_type_archive':
                        $item->url = get_post_type_archive_link($item->object);
                        break;
                    case 'post_type':
                        $item->url = get_permalink($item->object_id);
                        if ($item->title == '') $item->title = get_the_title($item->object_id);
                        break;
                    case 'taxonomy':
                        $term = NavMenusFactory::get_wp_object_from_nav_menu_post($item);
                        $item->url = get_term_link($term);
                        if ($item->title == '') $item->title = $term->name;
                        break;
                }
                $this->items[$id] = new WP_Post($item);
            }
        }
    }


    /**
     * @return WP_Term
     */
    public function get_wp_term() {
        if ( !$this->wp_term instanceof WP_Term) {
            if ($this->is_exists()) {
                $test_term = get_term_by('term_id', $this->id, 'nav_menu');
                if ($test_term instanceof WP_Term) {
                    $this->wp_term = $test_term;
                } else {
                    $this->wp_term = new WP_Term(new stdClass());
                }
            } else {
                $this->wp_term = new WP_Term(new stdClass());
            }
        }
        return $this->wp_term;
    }


    /**
     * @return bool
     */
    public function is_exists() {
        return intval($this->id) > 0;
    }


    /**
     * @param null|int|WP_Post $parent_id
     * @return array|WP_Post[]
     * @version 1.2
     */
    public function get_items($parent_id = null): array {
        if ($parent_id instanceof WP_Post) {
            $parent_id = $parent_id->ID;
        }
        return CacheFactory::get($this->id . '/' . $parent_id, __CLASS__ . '::$items', function() {
            if ($this->id == 0) {
                return [];
            } else {
                global $wpdb;
                $R = [];
                foreach ($this->items as $item) {
                    if (is_numeric(func_get_arg(0)) && $item->menu_item_parent != func_get_arg(0)) continue;
                    $R[] = $item;
                }
                return $R;
            }
        }, [ $parent_id ])->get_value();
    }


    /**
     * Return true, if items is exists
     * @param null|int $parent_id
     * @return bool
     */
    public function has_items($parent_id = null) {
        return count($this->get_items($parent_id)) > 0;
    }


    /**
     * @return array
     */
    public function get_associated_objects() {
        return CacheFactory::get($this->id, __CLASS__ . '::$associated_objects', function() {
            $R = [];
            foreach (self::get_items() as $nav_menu_item) {
                $R[$nav_menu_item->ID . ':' . NavMenusFactory::get_id_from_object($nav_menu_item)] = NavMenusFactory::get_wp_object_from_nav_menu_post($nav_menu_item);
            }
            return $R;
        })->get_value();
    }


    /**
     * Return locations array
     * @return array
     */
    public function get_locations() {
        return CacheFactory::get($this->id, __CLASS__ . '::$locations', function() {
            global $_wp_registered_nav_menus;
            $R = [];
            $nav_menu_locations = get_theme_mod('nav_menu_locations');
            if (is_array($nav_menu_locations)) foreach ($nav_menu_locations as $slug => $nav_menu_id) {
                if ($nav_menu_id == $this->id && array_key_exists($slug, $_wp_registered_nav_menus)) {
                    $R[] = $slug;
                }
            }
            return $R;
        })->get_value();
    }


    /**
     * @varsion 1.1
     * @param int|WP_post $parent_id
     * @param string      $ul_class
     * @param string      $li_class
     */
    public function the($parent_id = 0, $ul_class = '', $li_class = '') {
        if ($parent_id instanceof WP_Post) {
            $parent_id = $parent_id->ID;
        }
        ?>
        <ul class="<?= htmlentities($ul_class) ?>"><?php
        $items = $this->get_items($parent_id);
        if (is_array($items)) {
            foreach ($items as $item) {
                if ($item->menu_item_parent != $parent_id) continue;
                ?>
            <li class="<?= htmlentities($li_class) ?>"><a href="<?= $item->url ?>"><span><?= $item->title ?></span></a></li><?php
            }
        }
        ?></ul><?php
    }

}