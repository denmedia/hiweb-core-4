<?php

namespace hiweb\components\Includes;


use hiweb\components\Includes\Css\Media;
use hiweb\components\Includes\Css\Rel;
use hiweb\core\Options\Options;
use hiweb\core\Paths\Path;


/**
 * Class Css
 * @package hiweb\components\Includes
 * @version 1.2
 */
class Css extends Options {

    /** @var Path */
    private $Path;


    public function __construct(Path $Path) {
        parent::__construct();
        $this->Path = $Path;
    }


    /**
     * @return Path
     */
    public function Path(): Path {
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
     * @return array|Css|mixed|null
     */
    public function on_frontend($set = null) {
        return $this->_('on_frontend', $set);
    }


    /**
     * @param null|bool $set
     * @return array|Css|mixed|null
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
     * @return Css
     */
    public function set_admin($set = true): Css {
        $this->_('is_admin', $set);
        return $this;
    }


    /**
     * @param null|array $deeps
     * @return array|Css|mixed|null
     */
    public function deeps($deeps = null) {
        if (is_string($deeps) && $deeps != '') {
            $deeps = [ $deeps ];
        }
        return $this->_('deeps', $deeps);
    }


    /**
     * @return Rel
     */
    public function rel(): Rel {
        if ( !$this->_('rel') instanceof Rel) {
            $this->_('rel', new Rel($this));
        }
        return $this->_('rel');
    }


    /**
     * @return Media
     */
    public function media(): Media {
        if ( !$this->_('media') instanceof Media) {
            $this->_('media', new Media($this));
        }
        return $this->_('media');
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
     * Get html <link href="...style.css"/>
     * @return string
     */
    public function get_html(): string {
        $version = '';
        if ($this->path()->is_local() && @filemtime($this->path()->get_path_relative()) != '') $version = '?ver=' . @filemtime($this->Path()->file()->get_path());
        return '<link ' . $this->rel()() . ' id="' . $this->Path()->handle() . '" href="' . $this->Path()->url()->get_clear() . $version . '" ' . $this->media()() . ' />';
    }


    public function the_html() {
        echo $this->get_html();
    }

}