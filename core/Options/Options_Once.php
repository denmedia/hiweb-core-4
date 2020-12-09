<?php

namespace hiweb\core\Options;


/**
 * Используется для суб-опции, которая имеет тольуко одно значение
 * Class Options_Once
 * @package hiweb\core\Options
 * @version 1.1
 */
abstract class Options_Once extends Options {

    /**
     * @param null $option_key
     * @param null $default
     * @param bool $callIfFunction - call function if is callable
     * @return array|mixed|null
     * @version 1.2
     */
    protected function get($option_key = null, $default = null, $callIfFunction = true) {
        $R = $this->options_ArrayObject()->get_value('', $default);
        if ($callIfFunction && is_callable($R) && get_class($R) == 'Closure') $R = $R(func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
        return $R;
    }


    /**
     * @param      $value
     * @param null $null
     * @return Options|mixed
     */
    protected function set($null, $value) {
        parent::set('', $value);
        return $this->getParent_OptionsObject();
    }


    /**
     * Set or get once option value
     * @param null|mixed $value
     * @param null|mixed $default
     * @param null       $null
     * @param bool       $callIfFunction
     * @return array|\hiweb\core\Options\Options|mixed|null
     */
    public function _($value = null, $default = null, $null = null, $callIfFunction = true) {
        return parent::_('', $value, $default);
    }


    public function _get_optionsCollect() {
        return $this->_();
    }


}