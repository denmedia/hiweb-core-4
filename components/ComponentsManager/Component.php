<?php

namespace hiweb\components\ComponentsManager;


use hiweb\core\Debug\Debug;
use hiweb\core\Strings;


class Component {

    private $id = '';
    private $initCallback = null;
    private $callbackArgs = [];
    private $label = '';
    private $description = '';
    private $default_enable = false;


    public function __construct(string $initCallback) {
        $this->initCallback = $initCallback;
        $this->id = Strings::sanitize_id(ltrim($initCallback, '\\'));
        $this->label = basename(dirname($initCallback));
    }


    /**
     * @return string
     */
    public function get_id(): string {
        return $this->id;
    }


    /**
     * Set component label
     * @param $label
     * @return Component
     */
    public function set_label($label): Component {
        $this->label = $label;
        return $this;
    }


    /**
     * Get component label
     * @return string
     */
    public function get_label(): string {
        return $this->label;
    }


    /**
     * Set component description
     * @param $description
     * @return Component
     */
    public function set_description($description): Component {
        $this->description = $description;
        return $this;
    }


    /**
     * Get component description
     * @return string
     */
    public function get_description(): string {
        return $this->description;
    }


    /**
     * Set if component is default enable
     * @param bool $enable
     * @return $this
     */
    public function set_default_enable($enable = true): Component {
        $this->default_enable = (bool)$enable;
        return $this;
    }


    /**
     * Return true / false if component is default enabled
     * @return bool
     */
    public function get_default_enable(): bool {
        return $this->default_enable;
    }


    /**
     * Set callback args
     * @param array $args
     * @return Component
     */
    public function set_callback_args($args = []): Component {
        $this->callbackArgs = (array)$args;
        return $this;
    }


    /**
     * @return array
     */
    public function get_callback_args(): array {
        return (array)$this->callbackArgs;
    }


    /**
     * Init component
     * @return null|false|mixed
     */
    public function init(): ?bool {
        if (is_callable($this->initCallback)) {
            return call_user_func_array($this->initCallback, $this->get_callback_args());
        }
        return null;
    }


    /**
     * Return true if component is enable
     * @return bool
     */
    public function is_enable(): bool {
        return (bool)get_field($this->get_id(), 'hiweb-components');
    }

}