<?php

namespace hiweb\components\Breadcrumb;


use hiweb\components\Structures\Structure;
use hiweb\components\Structures\StructuresFactory;


/**
 * Class Breadcrumb
 * @package theme\breadcrumb
 * @version 2.0
 */
class Breadcrumb {

    /** @var string */
    private $id;
    private $contextObject;
    /** @var Structure */
    private $structure;
    /** @var array|Breadcrumb_Item[] */
    private $items;
    /** @var Breadcrumb_Item */
    private $current_breadcrumb_item;
    /** @var string[] */
    private $wrap_class = [ 'breadcrumb' ];
    /** @var string[] */
    private $item_class = [ 'breadcrumb-item' ];


    public function __construct($objectContext) {
        $this->id = StructuresFactory::get_id_from_object($objectContext);
        $this->contextObject = $objectContext;
        $this->structure = StructuresFactory::get($objectContext);
    }


    public function get_contextObject() {
        return $this->contextObject;
    }


    /**
     * @return Structure
     */
    public function get_structure(): Structure {
        return $this->structure;
    }


    /**
     * Add class to wrap(list) tag
     * @param $class
     * @return array
     */
    public function add_class_wrap($class): array {
        $class = is_array($class) ? $class : [ $class ];
        $this->wrap_class = array_merge($this->wrap_class, $class);
        return $this->wrap_class;
    }


    /**
     * @param bool $join
     * @return string|string[]|void
     */
    public function get_class_wrap($join = true) {
        return $join ? esc_attr(join(' ', $this->wrap_class)) : $this->wrap_class;
    }


    /**
     * Add class to item tag
     * @param $class
     * @return array
     */
    public function add_class_item($class): array {
        $class = is_array($class) ? $class : [ $class ];
        $this->item_class = array_merge($this->item_class, $class);
        return $this->item_class;
    }


    /**
     * @param bool $join
     * @return string|string[]|void
     */
    public function get_class_item($join = true) {
        return $join ? esc_attr(join(' ', $this->item_class)) : $this->item_class;
    }


    /**
     * @return Breadcrumb_Item[]
     */
    public function get_items(): array {
        if ( !is_array($this->items)) {
            $this->items = [];
            $this->current_breadcrumb_item = new Breadcrumb_Item($this, $this->get_structure());
            $this->items = $this->current_breadcrumb_item->get_parents();
            $this->items = array_reverse($this->items);
        }
        return $this->items;
    }


    public function the_html() {
        if (get_field('hide-mobile', BreadcrumbFactory::$admin_options_slug) && get_client()->is_mobile()) return;
        ///
        hw_template_part(HIWEB_THEME_PARTS . '/breadcrumb/before', '', $this);
        ///print home crumb
        if (get_field('home-enable', BreadcrumbFactory::$admin_options_slug)) {
            hw_template_part(HIWEB_THEME_PARTS . '/breadcrumb/item_home', '', $this);
        }
        ///print chain crumbs
        $chainLimit = absint(get_field('limit-chain', BreadcrumbFactory::$admin_options_slug));
        foreach ($this->get_items() as $index => $item) {
            if ($chainLimit < 0) break;
            $chainLimit --;
            hw_template_part(HIWEB_THEME_PARTS . '/breadcrumb/item_chain', '', $item);
        }
        ///print current page crumb
        if (get_field('current-enable', BreadcrumbFactory::$admin_options_slug) && ( !get_client()->is_mobile() || get_field('current-enable-mobile', BreadcrumbFactory::$admin_options_slug))) {
            hw_template_part(HIWEB_THEME_PARTS . '/breadcrumb/item_current', '', $this->current_breadcrumb_item);
        }
        hw_template_part(HIWEB_THEME_PARTS . '/breadcrumb/after', '', $this);
    }

}