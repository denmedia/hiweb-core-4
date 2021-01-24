<?php

namespace hiweb\components\ComponentsManager;




class ComponentsManager {


    /**
     * @return array|Component[]
     */
    static $components = [];


    /**
     * @param string $initCallable
     * @return Component
     */
    static function register_component(string $initCallable): Component {
        $component = new Component($initCallable);
        self::$components[$component->get_id()] = $component;
        return $component;
    }


    /**
     * @return array|Component[]
     */
    static function get_components(): array {
        return self::$components;
    }

}