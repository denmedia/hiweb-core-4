<?php

namespace hiweb\components\Console;


/**
 * Class Console
 * @package hiweb\components\Console
 * @version 1.1
 */
class Console {

    public $content = '';
    public $type = 'info';
    public $groupTitle = '';
    public $debugStatus = false;
    public $addition_data;


    /**
     * Console constructor.
     * @param string     $content
     * @param string     $type
     * @param null|mixed $addition_data
     */
    public function __construct($content = '', $type = 'info', $addition_data = null) {
        $this->content = $content;
        $this->type = $type;
        $this->addition_data = $addition_data;
    }


    /**
     * @param null|string $content
     */
    public function set_content($content = null) {
        $this->content = $content;
    }


    /**
     * @param mixed $addition_data
     */
    public function set_addition_data($addition_data) {
        $this->addition_data = $addition_data;
    }


    /**
     * @return string
     */
    public function type() {
        $allow_types = [ 'info', 'log', 'warn', 'error' ];
        return array_search($this->type, $allow_types) === false ? 'info' : $this->type;
    }


    /**
     * @param $variable
     * @return string
     */
    private function get_variable_type($variable) {
        if (is_null($variable)) {
            return '[NULL]';
        } elseif (is_array($variable)) {
            return '[array → ' . count($variable) . ']';
        } elseif (is_object($variable)) {
            return '[object → ' . get_class($variable) . ']';
        } elseif (is_string($variable)) {
            return '[string → ' . strlen($variable) . ']';
        } else {
            return '[' . gettype($variable) . ']';
        }
    }


    /**
     * Get parsed data from array and object. Limit depth.
     * @param     $data
     * @param int $depth
     * @return array|string
     */
    private function get_variable_data($data, $depth = 5) {
        if ($depth < 0) {
            if (is_array($data) || is_object($data)) {
                return self::get_variable_type($data);
            } else {
                return $data;
            }
        }
        $R = [];
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $R[($key == '' ? '' : $key . ' ') . self::get_variable_type($val)] = $this->get_variable_data($val, $depth - 1);
            }
            return $R;
        } elseif (is_object($data)) {
            $pattern = '/^[\s\S]*' . preg_quote(get_class($data)) . '/';
            foreach ((array)$data as $key => $value) {
                $key = preg_replace($pattern, '', $key);
                $R[($key == '' ? '' : $key . ' ') . self::get_variable_type($value)] = $this->get_variable_data($value, $depth - 1);
            }
            return $R;
        } else {
            return $data;
        }
    }


    /**
     * @return string
     * @version 1.7
     */
    public function html(): string {
        if (defined('SHORTINIT') && SHORTINIT) return '';
        if ( !$this->debugStatus || (function_exists('is_user_logged_in') && function_exists('is_user_admin') && function_exists('is_super_admin') && is_user_logged_in() && (is_user_admin() || is_super_admin()))) {
            $params = [ json_encode([ self::get_variable_type($this->content) => $this->get_variable_data($this->content) ]) ];
            if (is_string($this->addition_data) && strlen($this->addition_data) > 0) $this->addition_data = [ $this->addition_data ];
            if (is_array($this->addition_data) && count($this->addition_data) > 0) $params[] = json_encode([ 'addition_data' => $this->addition_data ]);
            $params = implode(', ', $params);
            return "<script>console.{$this->type()}({$params});</script>";
        }
        return '';
    }


    /**
     * Print html
     */
    public function the() {
        $R = $this->html();
        echo $R;
    }


    /**
     * @param bool $set
     * @return Console
     */
    public function set_groupTitle($set = true) {
        $this->groupTitle = $set;
        return $this;
    }


    /**
     * @param bool $set
     * @return $this
     */
    public function set_debugStatus($set = true) {
        $this->debugStatus = $set;
        return $this;
    }

}