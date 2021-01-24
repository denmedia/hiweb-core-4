<?php

namespace hiweb\components\Includes;


use hiweb\core\Options\Options;
use hiweb\core\Paths\Path;


/**
 * Class Js
 * @package hiweb\components\Includes
 * @version 1.2
 */
class Js extends Options {

    /** @var Path */
    private $Path;


    public function __construct(Path $Path) {
        parent::__construct();
        $this->Path = $Path;
    }


    /**
     * @return Path
     */
    public function path(): Path {
        return $this->Path;
    }


    /**
     * Put file include to footer OR get bool is in footer
     * @param null|bool $set
     * @return Js|bool
     */
    public function to_footer($set = null) {
        return $this->_('footer', $set);
    }


    /**
     * @param null|bool $set
     * @return array|Js|mixed|null
     */
    public function on_frontend($set = null) {
        return $this->_('on_frontend', $set);
    }


    /**
     * @param null|bool $set
     * @return array|Js|mixed|null
     */
    public function on_admin($set = null) {
        return $this->_('on_admin', $set);
    }


    /**
     * @param null|bool $set
     * @return array|Js|mixed|null
     */
    public function on_login($set = null) {
        return $this->_('on_login', $set);
    }


    /**
     * @param bool $set
     * @return $this|array|Js|mixed|null
     */
    public function async($set = true) {
        if ($set) return $this->_('async', 'async'); else $this->remove('async');
        return $this;
    }


    /**
     * @param null $set
     * @return $this|array|Js|mixed|null
     */
    public function defer($set = null) {
        if (is_bool($set)) {
            if ($set) return $this->_('async', 'defer'); else $this->remove('async');
        }
        return $this;
    }


    /**
     * @param null $deeps
     * @return array|Js|mixed|null
     */
    public function deeps($deeps = null) {
        if (is_string($deeps) && $deeps != '') {
            $deeps = [ $deeps ];
        }
        return $this->_('deeps', $deeps);
    }


    /**
     * Hide that script for web bots, like Google, Yandex, Insights
     * @param null $set
     * @return array|Js|mixed|null
     */
    public function hide_forWebBots($set = null) {
        return $this->_('hide_forWebBots', $set);
    }


    /**
     * Return JS HTML
     * @return string
     */
    public function get_html(): string {
        $version = '';
        if ($this->path()->is_local() && @filemtime($this->path()->get_path_relative()) != '') $version = '?ver=' . @filemtime($this->path()->get_path_relative());
        return '<script src="' . $this->path()->url()->get() . $version . '" ' . $this->_('async') . ' data-handle="' . $this->path()->handle() . '"></script>';
    }


    public function the_html() {
        echo $this->get_html();
    }

}