<?php

namespace hiweb\components\Breadcrumb;


use hiweb\components\Structures\Structure;


class Breadcrumb_Item {

    /** @var Breadcrumb */
    private $breadcrumb;
    /** @var Structure */
    private $structure;
    /** @var Breadcrumb_Item */
    private $parents;


    public function __construct(Breadcrumb $breadcrumb, Structure $structure) {
        $this->breadcrumb = $breadcrumb;
        $this->structure = $structure;
    }


    /**
     * @return array|Breadcrumb_Item[]
     */
    public function get_parents(): array {
        if ( !is_array($this->parents)) {
            $this->parents = [];
            foreach ($this->structure->get_parents(false) as $structure) {
                $this->parents[] = new Breadcrumb_Item($this->get_breadcrumb(), $structure);
            }
        }
        return $this->parents;
    }


    /**
     * @return Breadcrumb
     */
    public function get_breadcrumb(): Breadcrumb {
        return $this->breadcrumb;
    }


    /**
     * @return Structure
     */
    public function get_structure(): Structure {
        return $this->structure;
    }


    /**
     * @param bool $force_raw
     * @return string
     */
    public function get_title($force_raw = true): string {
        return $this->get_structure()->get_title($force_raw);
    }


    /**
     * @return bool|string|\WP_Error
     */
    public function get_url() {
        return $this->get_structure()->get_url();
    }

}