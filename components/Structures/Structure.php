<?php

namespace hiweb\components\Structures;


use hiweb\components\NavMenus\NavMenusFactory;
use hiweb\core\Cache\CacheFactory;
use hiweb\core\hidden_methods;
use hiweb\core\Paths\PathsFactory;
use WooCommerce;
use WP_Error;
use WP_Post;
use WP_Post_Type;
use WP_Taxonomy;
use WP_Term;


/**
 * Class for control the element in site structure
 * @package hiweb\components\Structures
 * @version 1.1
 */
class Structure {

    use hidden_methods;


    private $id;
    private $wp_object;


    public function __construct($wp_object, $objectId = null) {
        $this->wp_object = $wp_object;
        $this->id = !is_string($objectId) ? StructuresFactory::get_id_from_object($wp_object) : $objectId;
        ///Preloader
        //TODO
    }


    /**
     * @return \WP_Post|\WP_Post_Type|\WP_Term|\WP_User|null
     */
    public function get_wp_object() {
        return $this->wp_object;
    }


    /**
     * @return string
     */
    public function get_id() {
        return $this->id;
    }


    /**
     * @return bool
     */
    public function is_front_page() {
        return $this->id == 'home' || ($this->wp_object instanceof \WP_Post && StructuresFactory::get_front_page_id() == $this->wp_object->ID);
    }


    /**
     * @return bool
     */
    public function is_page_for_posts() {
        return ($this->wp_object instanceof \WP_Post && StructuresFactory::get_blog_id() == $this->wp_object->ID);
    }


    /**
     * @return bool
     */
    public function is_search() {
        return $this->id == 'search';
    }


    /**
     * @return bool
     */
    public function is_exists() {
        return strpos($this->id, ':') !== 0;
    }


    /**
     * @return bool|false|string|WP_Error
     */
    public function get_url() {
        if ($this->is_search()) {
            return get_home_url() . '?s=' . PathsFactory::get()->url()->params()->_('s');
        } elseif ($this->wp_object instanceof WP_Post) {
            return get_permalink($this->wp_object);
        } elseif ($this->wp_object instanceof WP_Term) {
            return get_term_link($this->wp_object);
        } elseif ($this->wp_object instanceof WP_Post_Type && $this->wp_object->public && $this->wp_object->publicly_queryable && $this->wp_object->has_archive) {
            return get_post_type_archive_link($this->wp_object->name);
        } elseif ($this->wp_object instanceof \WP_User) {
            return get_author_posts_url($this->wp_object->ID);
        }
        return get_home_url();
    }


    /**
     * @param bool $force_raw
     * @return mixed|string
     */
    public function get_title($force_raw = true) {
        if ($this->is_search()) {
            return apply_filters('\hiweb\components\Structures\Structure::get_title', 'Результаты поиска', $this->wp_object, $force_raw, $this);
        } elseif ($this->wp_object instanceof WP_Post) {
            return apply_filters('\hiweb\components\Structures\Structure::get_title', $force_raw ? $this->wp_object->post_title : get_the_title($this->wp_object), $this->wp_object, $force_raw, $this);
        } elseif ($this->wp_object instanceof WP_Term) {
            return apply_filters('\hiweb\components\Structures\Structure::get_title', $this->wp_object->name, $this->wp_object, $force_raw, $this);
        } elseif ($this->wp_object instanceof \WP_User) {
            return apply_filters('\hiweb\components\Structures\Structure::get_title', $this->wp_object->name, $this->wp_object, $force_raw, $this);
        } elseif ($this->wp_object instanceof WP_Post_Type) {
            if ($this->wp_object->name == 'product' && function_exists('WC')) {
                $shop_page_id = get_option('woocommerce_shop_page_id');
                if (get_post($shop_page_id) instanceof WP_Post && get_post($shop_page_id)->post_type == 'page') {
                    $title = get_the_title($shop_page_id);
                    if ($title != '') return $title;
                }
            }
            return apply_filters('\hiweb\components\Structures\Structure::get_title', $this->wp_object->label, $this->wp_object, $force_raw, $this);
        } else {
            return get_bloginfo('name');
        }
    }


    /**
     * @return array|WP_Post[]
     */
    public function get_parent_front_page() {
        return CacheFactory::get($this->id, __METHOD__, function() {
            if ( !$this->is_front_page() && StructuresFactory::get_front_page() instanceof WP_Post) {
                return [ StructuresFactory::get_front_page() ];
            } else {
                return [];
            }
        })->get_value();
    }


    /**
     * @return array|WP_Post[]
     */
    public function get_parent_wp_post() {
        return CacheFactory::get($this->id, __METHOD__, function() {
            if ($this->wp_object instanceof WP_Post) {
                if ($this->wp_object->post_parent != 0) {
                    $wp_post_test = get_post($this->wp_object->post_parent);
                    if ($wp_post_test instanceof WP_Post && $this->wp_object != $wp_post_test) return [ $wp_post_test ];
                }
            }
            return [];
        })->get_value();
    }


    /**
     * @param null|string|array $taxonomy_filter
     * @return array|WP_Term[]
     */
    public function get_parent_wp_terms($taxonomy_filter = null) {
        if (is_string($taxonomy_filter) && $taxonomy_filter != '') $taxonomy_filter = [ $taxonomy_filter ]; elseif ( !is_array($taxonomy_filter)) $taxonomy_filter = null;
        ///
        return CacheFactory::get([ $taxonomy_filter, $this->id ], __METHOD__, function() {
            $taxonomies_filter = func_get_arg(0);
            if ($this->wp_object instanceof WP_Post) {
                $taxonomies = is_array($taxonomies_filter) ? $taxonomies_filter : get_object_taxonomies($this->wp_object->post_type);
                $terms_result = [];
                foreach ($taxonomies as $taxonomy) {
                    if ( !get_taxonomy($taxonomy)->public) continue;
                    $terms = get_the_terms($this->wp_object, $taxonomy);
                    if (is_array($terms)) $terms_result = array_merge($terms_result, $terms);
                }
                return $terms_result;
            } elseif ($this->wp_object instanceof WP_Term && $this->wp_object->parent != 0) {
                $wp_term_test = get_term($this->wp_object->parent);
                if ($wp_term_test instanceof WP_Term && $this->wp_object != $wp_term_test) {
                    return [ $wp_term_test ];
                }
            }
            return [];
        }, [ $taxonomy_filter ])->get_value();
    }


    /**
     * @return array
     */
    public function get_parent_blog_page() {
        return CacheFactory::get($this->id, __METHOD__, function() {
            $R = [];
            if (StructuresFactory::get_blog_id() != 0) {
                if ($this->wp_object instanceof WP_Post) {
                    if ($this->wp_object->post_type == 'post') {
                        $R[] = get_post(StructuresFactory::get_blog_id());
                    }
                } elseif ($this->wp_object instanceof WP_Term) {
                    $taxonomy = get_taxonomy($this->wp_object->taxonomy);
                    if ($taxonomy instanceof WP_Taxonomy) {
                        foreach ($taxonomy->object_type as $post_type) {
                            if ($post_type == 'post') {
                                $R[] = get_post(StructuresFactory::get_blog_id());
                                break;
                            }
                        }
                    }
                }
            }
            return $R;
        })->get_value();
    }


    /**
     * @return WP_Post_Type[]
     */
    public function get_parent_wp_post_type() {
        return CacheFactory::get($this->id, __METHOD__, function() {
            $R = [];
            if ($this->wp_object instanceof WP_Post) {
                $post_type_object = get_post_type_object($this->wp_object->post_type);
                if ($post_type_object->public && $post_type_object->has_archive) {
                    $R[$this->wp_object->post_type] = $post_type_object;
                }
            } elseif ($this->wp_object instanceof WP_Term) {
                $taxonomy = get_taxonomy($this->wp_object->taxonomy);
                if ($taxonomy instanceof WP_Taxonomy) {
                    foreach ($taxonomy->object_type as $post_type) {
                        $post_type_object = get_post_type_object($post_type);
                        if ($post_type_object->public && $post_type_object->has_archive) {
                            $R[$post_type] = $post_type_object;
                        }
                    }
                }
            }
            return $R;
        })->get_value();
    }


    /**
     * @return WP_Post[]
     */
    public function get_parent_woocommerce_shop_page() {
        return CacheFactory::get($this->id, __METHOD__, function() {
            $R = [];
            if (function_exists('WC') && WC() instanceof WooCommerce) {
                if ($this->wp_object instanceof WP_Post && in_array($this->wp_object->post_type, apply_filters('rest_api_allowed_post_types', []))) {
                    $wp_post_test = get_post(wc_get_page_id('shop'));
                    if ($wp_post_test instanceof WP_Post && $wp_post_test != $this->wp_object) {
                        $R[$wp_post_test->ID] = $wp_post_test;
                    }
                } elseif ($this->wp_object instanceof WP_Term) {
                    $taxonomy = get_taxonomy($this->wp_object->taxonomy);
                    foreach ($taxonomy->object_type as $post_type) {
                        $post_type_object = get_post_type_object($post_type);
                        if ($post_type_object->public && in_array($post_type, apply_filters('rest_api_allowed_post_types', []))) {
                            $wp_post_test = get_post(wc_get_page_id('shop'));
                            if ($wp_post_test instanceof WP_Post && $wp_post_test != $this->wp_object) {
                                $R[$wp_post_test->ID] = $wp_post_test;
                            }
                        }
                    }
                }
            }
            return $R;
        })->get_value();
    }


    /**
     * @return array
     */
    public function get_parent_wp_object_by_nav() {
        return CacheFactory::get($this->id, __METHOD__, function() {
            $R = [];
            foreach (get_nav_menu_locations() as $location) {
                /** @var WP_Post $wp_nav_item */
                $nav_items = [];
                foreach (wp_get_nav_menu_items($location) as $nav_menu_item) {
                    $nav_items[$nav_menu_item->ID] = $nav_menu_item;
                }

                foreach ($nav_items as $wp_nav_item) {
                    if (rtrim($wp_nav_item->url, '/') == rtrim($this->get_url(), '/') && $wp_nav_item->menu_item_parent != 0) {
                        $parent_menu_nav_item = $nav_items[$wp_nav_item->menu_item_parent];
                        $object = StructuresFactory::get_object_from_id(NavMenusFactory::get_id_from_object($wp_nav_item));
                        if (is_object($object) && $this->wp_object != $object) {
                            if (get_post_type_object($object->post_type) instanceof \WP_Post_Type && get_post_type_object($object->post_type)->public) {
                                $R[StructuresFactory::get_id_from_object($object)] = $object;
                            }
                        }
                    }
                }
            }
            return $R;
        })->get_value();
    }


    /**
     * @return array|WP_Post[]|WP_Post_Type[]|WP_Term[]
     */
    public function get_parent_wp_object_variants() {
        return CacheFactory::get($this->id, __METHOD__, function() {
            $R = [];
            //TODO: сделать возможность установки приморитетов
            if ($this->wp_object instanceof WP_Post) {
                if (count($this->get_parent_wp_post()) > 0) {
                    $R = $this->get_parent_wp_post();
                } elseif (count($this->get_parent_wp_terms()) > 0) {
                    $R = $this->get_parent_wp_terms();
                } elseif (count($this->get_parent_wp_object_by_nav()) > 0) {
                    $R = $this->get_parent_wp_object_by_nav();
                } elseif (count($this->get_parent_blog_page()) > 0) {
                    $R = $this->get_parent_blog_page();
                } elseif (count($this->get_parent_wp_post_type()) > 0) {
                    $R = $this->get_parent_wp_post_type();
                } elseif (count($this->get_parent_woocommerce_shop_page()) > 0) {
                    $R = $this->get_parent_woocommerce_shop_page();
                } else {
                    $R = [];
                }
            } elseif ($this->wp_object instanceof WP_Term) {
                if (count($this->get_parent_wp_terms()) > 0) {
                    $R = $this->get_parent_wp_terms();
                } elseif (count($this->get_parent_wp_object_by_nav()) > 0) {
                    $R = $this->get_parent_wp_object_by_nav();
                } elseif (count($this->get_parent_blog_page()) > 0) {
                    $R = $this->get_parent_blog_page();
                } elseif (count($this->get_parent_wp_post_type()) > 0) {
                    $R = $this->get_parent_wp_post_type();
                } elseif (count($this->get_parent_woocommerce_shop_page()) > 0) {
                    $R = $this->get_parent_woocommerce_shop_page();
                } else {
                    $R = [];
                }
            } elseif ($this->wp_object instanceof WP_Post_Type) {
                if (count($this->get_parent_wp_object_by_nav()) > 0) {
                    $R = $this->get_parent_wp_object_by_nav();
                } else {
                    $R = [];
                }
            } elseif ($this->id == '404') {
                $R = $this->get_parent_front_page();
            } elseif ($this->id == 'search') {
                $R = $this->get_parent_front_page();
            }
            return $R;
        })->get_value();
    }


    public function get_parents_wp_object_variants($return_current = true) {
        return CacheFactory::get($this->id, __METHOD__, function() {
            $parent_objects = $this->get_parent_wp_object_variants();
            $R = (func_get_arg(0) && is_object($this->wp_object)) ? [ $this->get_id() => $this->wp_object ] : [];
            if (is_array($parent_objects) && count($parent_objects) > 0) {
                $parent_structure = StructuresFactory::get(reset($parent_objects));
                $R = array_merge($R, [ $parent_structure->get_id() => $parent_structure ], $parent_structure->get_parent_wp_object_variants());
            }
            return $R;
        }, [ $return_current ])->get_value();
    }


    /**
     * @param bool $return_current - возвращать вместе с текущей структурой в массиве
     * @return Structure[]
     */
    public function get_parents($return_current = true) {
        return CacheFactory::get($this->id, __METHOD__, function() {
            $R = func_get_arg(0) ? [ $this->get_id() => $this ] : [];
            $parent_objects = $this->get_parent_wp_object_variants();
            if (is_array($parent_objects) && count($parent_objects) > 0) {
                $parent_structure = StructuresFactory::get(reset($parent_objects));
                $R = array_merge($R, [ $parent_structure->get_id() => $parent_structure ], $parent_structure->get_parents());
            }
            return $R;
        }, [ $return_current ])->get_value();
    }


    /**
     * @return array
     */
    public function get_parent_urls() {
        return CacheFactory::get($this->id, __METHOD__, function() {
            $R = [];
            foreach ($this->get_parents() as $structure) {
                $R[$structure->get_url()] = $structure->get_title();
            }
            return $R;
        })->get_value();
    }


    /**
     * @param null $wp_object
     * @return bool
     */
    public function has_children_object($wp_object = null) {
        if (is_null($wp_object) && function_exists('get_queried_object')) $wp_object = get_queried_object();
        if ($this->id == StructuresFactory::get_id_from_object($wp_object)) {
            return true;
        } else {
            foreach (StructuresFactory::get($wp_object)->get_parents_wp_object_variants() as $variants_object) {
                foreach ($variants_object as $variant) {
                    if ($this->id == StructuresFactory::get_id_from_object($variant)) return true;
                }
            }
        }
        return false;
    }


    /**
     * @param null $wp_object
     * @return bool
     */
    public function has_parents_object($wp_object = null) {
        if (is_null($wp_object) && function_exists('get_queried_object')) $wp_object = get_queried_object();
        $wp_object_id = StructuresFactory::get_id_from_object($wp_object);
        if ($this->id == $wp_object_id) {
            return true;
        } else {
            foreach ($this->get_parents_wp_object_variants() as $variants_object) {
                foreach ($variants_object as $variant) {
                    if (StructuresFactory::get_id_from_object($variant) == $wp_object_id) return true;
                }
            }
        }
        return false;
    }


    /**
     * Return true, if this structure object is current queried object
     * @return bool
     * @version 1.0
     */
    public function is_current() {
        if ( !function_exists('get_queried_object')) return false;
        $current_object_id = StructuresFactory::get_id_from_object(get_queried_object());
        $this_wp_object_id = $this->wp_object;
        if ($this->wp_object instanceof WP_Post && $this->wp_object->post_type == 'nav_menu_item') {
            $this_wp_object_id = StructuresFactory::get_id_from_object(NavMenusFactory::get_wp_object_from_nav_menu_post($this->wp_object));
        }
        if ($current_object_id == 'post_type_archive:product' && StructuresFactory::get_wc_shop_page_id() > 0 && StructuresFactory::get_id_from_object(get_post(StructuresFactory::get_wc_shop_page_id())) == $this_wp_object_id) {
            return true;
        }
        return ($current_object_id === $this_wp_object_id);
    }

}