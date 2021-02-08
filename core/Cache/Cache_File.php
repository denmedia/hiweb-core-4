<?php

namespace hiweb\core\Cache;


use hiweb\components\Console\ConsoleFactory;
use hiweb\core\Paths\Path_File;
use hiweb\core\Paths\PathsFactory;
use hiweb\core\Strings;


class Cache_File {

    //private $dir = WP_CONTENT_DIR . '/cache/hiweb';

    /** @var Cache */
    private $Cache;
    /** @var Path_File */
    private $File;
    /** @var int */
    private $lifetime_seconds = 0;
    /** @var bool */
    private $enable = false;
    /** @var string */
    private $file_extension = 'json';
    /** @var int */
    static $option_default_life_time_seconds = 86400;


    public function __construct(Cache $Cache, $fileName = null) {
        $this->Cache = $Cache;
        $this->lifetime_seconds = self::$option_default_life_time_seconds;
        if ( !is_string($fileName) || trim($fileName) == '') {
            $fileName = preg_replace('/^hiweb[\s\-_]/', '', Strings::sanitize_id($Cache->get_variable_name()));
        } else {
            $fileName = Strings::sanitize_id($fileName, '_', 48);
        }
        $this->File = PathsFactory::get(CacheFactory::$cache_dir . '/' . preg_replace('/^hiweb[\s\-_]/', '', Strings::sanitize_id($Cache->get_group_name())) . '/' . $fileName . '.' . $this->file_extension)->file();
    }


    /**
     * Enable file cache (to write or read cache data from file)
     * @return $this
     */
    public function set_enable(): Cache_File {
        if ( !$this->enable) {
            if ( !file_exists($this->File->get_dirname())) mkdir($this->File->get_dirname(), 0755, true);
            if ($this->cache()->is_set()) {
                $this->set_value($this->Cache->get_value());
            }
            $this->enable = true;
        }
        return $this;
    }


    /**
     * @return bool
     */
    public function is_enable(): bool {
        return !CacheFactory::$disable_cache_file_read && $this->enable;
    }


    /**
     * @return Cache
     */
    public function cache(): Cache {
        return $this->Cache;
    }


    /**
     * @return Path_File
     */
    public function file(): Path_File {
        return $this->File;
    }


    /**
     * Read and return value from file
     * @return mixed|null
     */
    public function get_value() {
        $R = null;
        if ($this->file()->is_readable()) {
            $file_data = json_decode($this->file()->get_content(), true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $R = $file_data;
            } else {
                ConsoleFactory::add('Error while read cache file [' . $this->file()->get_path_relative() . ']', 'warn', __CLASS__, [], true);
            }
        } else {
            ConsoleFactory::add('Can\'t read cache file [' . $this->file()->get_path_relative() . ']', 'warn', __CLASS__, [], true);
        }
        return $R;
    }


    /**
     * Write current cache value to file
     * @param mixed $value
     * @param bool  $force
     * @return bool|int
     */
    public function set_value($value, $force = false) {
        if ($this->is_alive() && !$force) {
            //файл еще актуален, и нет неолбходимости его переписывать
            return true;
        } elseif ( !$this->file()->is_exists() || $this->file()->is_writable() || $force) {
            $R = $this->file()->set_content(json_encode($value));
            if ($R === false) {
                ConsoleFactory::add('Can\'t write cache file [' . $this->file()->get_path_relative() . ']', 'warn', __CLASS__, [], true);
            }
            return $R;
        } else {
            ConsoleFactory::add('Can\'t write cache file [' . $this->file()->get_path_relative() . '], file is not writable', 'warn', __CLASS__, [], true);
        }
        return false;
    }


    /**
     * Set file cache lifetime in seconds
     * 3600 - hour, 86400 - day, 604800 - week, 2.628e+6 - month, 31535965 - year
     * @param int $seconds
     * @return Cache_File
     */
    public function set_lifetime($seconds = 86400): Cache_File {
        $this->lifetime_seconds = $seconds;
        return $this;
    }


    /**
     * Return delta time of cache life between born and die
     * @return int
     */
    public function get_lifetime(): int {
        return intval($this->lifetime_seconds);
    }


    /**
     * Return end life timestamp
     * @return int
     */
    public function get_lifetime_stamp(): int {
        if ( !$this->file()->is_exists() || !$this->file()->is_file()) return 0;
        return filemtime($this->file()->get_path()) + $this->get_lifetime();
    }


    /**
     * Return true, if cache is actually (alive)
     * @return bool
     */
    public function is_alive(): bool {
        if ( !$this->file()->is_exists() || !$this->file()->is_file()) return false;
        return $this->get_lifetime_stamp() > microtime(true);
    }

}