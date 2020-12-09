<?php

namespace hiweb\core\ArrayObject;


use hiweb\core\ArrayObject\ArrayObject;
use hiweb\core\Cache\CacheFactory;


/**
 * Class ArrayObject_Rows
 * @version 1.3
 * @package hiweb\core\ArrayObject
 */
class ArrayObject_Rows {

    /** @var ArrayObject */
    private $array;

    private $rows = null;
    /** @var null|mixed */
    private $current_row = null;
    /** @var null|string|int */
    private $current_row_key = null;
    /** @var null|ArrayObject_Rows */
    private $current_sub_rows = null;
    ///
    /** @var null|ArrayObject_Rows */
    private $current_sub_field_rows = null;


    /**
     * Return dummy array rows
     * @return ArrayObject_Rows
     */
    private static function get_dummy_rows() {
        return CacheFactory::get(__FUNCTION__, __CLASS__, function() {
            return new ArrayObject_Rows([]);
        })->get_value();
    }


    public function __construct($array) {
        if ($array instanceof ArrayObject) {
            $this->array = $array;
        } else {
            $this->array = new ArrayObject($array);
        }
    }


    /**
     * @return ArrayObject
     */
    public function arrayObject() {
        return $this->array;
    }


    /**
     * Reset rows of ArrayObject to first
     * @return int
     */
    public function reset() {
        if ($this->array->is_empty()) return 0;
        $this->rows = $this->array->get();
        $this->current_row = null;
        $this->current_sub_rows = null;
        $this->current_row_key = null;
        $this->current_sub_field_rows = null;
        return count($this->rows);
    }


    public function have() {
        if ($this->array->is_empty()) return false;
        if ( !is_array($this->rows)) $this->reset();
        if (count($this->rows) == 0) {
            $this->reset();
            return false;
        }
        $this->current_sub_field_rows = null;
        return true;
    }


    /**
     * @return mixed|null
     */
    public function the() {
        if (is_array($this->rows) && count($this->rows) > 0) {
            reset($this->rows);
            $this->current_row_key = key($this->rows);
            $this->current_row = $this->rows[$this->current_row_key];
            unset($this->rows[$this->current_row_key]);
            $this->current_sub_rows = new ArrayObject_Rows($this->current_row);
            return $this->current_row;
        }
        return null;
    }


    /**
     * @param $callable - user function, call event array item
     * @return array - return array of result call user function
     */
    public function each($callable) {
        $R = [];
        if (is_callable($callable)) {
            $this->reset();
            if ($this->have()) {
                while($this->have()) {
                    $this->the();
                    $R[$this->get_current_key()] = call_user_func_array($callable, [ $this->get_current_key(), is_array($this->get_current()) ? new ArrayObject($this->get_current()) : $this->get_current(), $this ]);
                }
            }
        }
        return $R;
    }


    /**
     * Reverse rows array
     * @return false|array
     */
    public function reverse() {
        return is_array($this->rows) ? array_reverse($this->rows) : false;
    }


    /**
     * @return null
     */
    public function get_current() {
        return is_array($this->current_row) ? new ArrayObject($this->current_row) : $this->current_row;
    }


    public function get_current_key() {
        return $this->current_row_key;
    }


    /**
     * @return bool
     */
    public function is_first() {
        if ( !is_array($this->rows) || $this->array->is_empty()) return false;
        return (count($this->rows) + 1) == $this->array->count();
    }


    /**
     * @return bool
     */
    public function is_last() {
        if ( !is_array($this->rows) || $this->array->is_empty()) return false;
        return count($this->rows) == 0;
    }


    /**
     * @return bool
     */
    public function is_sub_rows() {
        return is_array($this->current_row) && $this->current_sub_rows instanceof ArrayObject_Rows;
    }


    /**
     * @param string $col_id
     * @return bool
     */
    public function have_sub_field($col_id) {
        if ($this->is_sub_rows()) return $this->current_sub_rows->arrayObject()->key_exists($col_id); else return false;
    }


    /**
     * Return sub field value
     * @param null $col_id
     * @param null $default
     * @return array|mixed|null
     */
    public function get_sub_field($col_id = null, $default = null) {
        if ($this->is_sub_rows()) return $this->current_sub_rows->arrayObject()->_($col_id, $default); else return $default;
    }


    /**
     * @return array|mixed|null
     */
    public function get_layout() {
        return $this->get_sub_field('_flex_row_id');
    }


    /**
     * @alias get_layout()
     * @return array|mixed|null
     */
    public function get_row_layout() {
        return $this->get_layout();
    }


    /**
     * Return current row index
     * @return int
     */
    public function get_index() {
        return $this->array->count() - (count($this->rows) + 1);
    }


    /**
     * @param $col_id
     * @return ArrayObject_Rows
     */
    public function get_sub_field_rows($col_id) {
        if ( !is_array($this->get_sub_field($col_id))) return self::get_dummy_rows();
        if ( !$this->current_sub_field_rows instanceof ArrayObject_Rows) {
            $this->current_sub_field_rows = new ArrayObject_Rows($this->get_sub_field($col_id));
        }
        return $this->current_sub_field_rows;
    }


    /**
     * Return all count
     * @return int
     */
    public function get_count() {
        return $this->arrayObject()->count();
    }


    /**
     *
     */
    public function get_count_prev() {
        if ( !is_array($this->rows)) return 0;
        return $this->get_count() - count($this->rows) - 1;
    }


    public function get_count_next() {
        if ( !is_array($this->rows)) return 0;
        return count($this->rows);
    }


}