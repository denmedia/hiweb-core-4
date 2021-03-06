<?php

namespace hiweb\core\Paths;


use hiweb\components\Console\ConsoleFactory;
use hiweb\components\Includes\IncludesFactory;
use hiweb\core\ArrayObject\ArrayObject;
use hiweb\core\Cache\CacheFactory;
use hiweb\core\hidden_methods;


/**
 * Class Path_File
 * @package hiweb\core\Paths
 * @version 1.2
 */
class Path_File {

    /** @var Path */
    private $Path;
    /** @var Path_Image */
    private $cache_Image;
    private $original_path;
    private $relative_path;
    private $absolute_path;
    /** @var array */
    private $cache_subFiles = [];


    use hidden_methods;


    public function __construct(Path $Path) {
        $this->Path = $Path;
        $this->prepare();
    }


    /**
     * @return Path
     */
    public function path(): Path {
        return $this->Path;
    }


    /**
     * @return Path_Url
     */
    public function url(): Path_Url {
        return $this->path()->url();
    }


    /**
     * @return Path_Image
     */
    public function image(): Path_Image {
        if ( !$this->cache_Image instanceof Path_Image) {
            $this->cache_Image = new Path_Image($this->path());
        }
        return $this->cache_Image;
    }


    /**
     * Prepare file data
     */
    private function prepare() {
        $this->original_path = $this->path()->get_original_path();
        if ($this->path()->is_url()) {

            if ($this->path()->is_local()) {
                $this->original_path = $this->url()->dirs()->join('/');
            } else {
                $pattern = '/^(?>[\w]+:)?(?>\/\/)?[а-яА-ЯЁёa-zA-Z0-9_\-.]+\/(?<path>[^?]+)\/?(?>\?.*)?/im';
                preg_match_all($pattern, $this->path()->get_original_path(), $matches);
                if (isset($matches['path'][0])) {
                    $this->original_path = $matches['path'][0];
                }
            }
        }
        ///
        if ($this->path()->is_relative()) {
            $this->absolute_path = PathsFactory::get_root_path() . '/' . ltrim($this->original_path,'\\/');
            $this->relative_path = trim($this->original_path, '\\/');
        } elseif ($this->path()->is_absolute()) {
            $this->absolute_path = $this->original_path;
            $this->relative_path = trim(str_replace(PathsFactory::get_root_path(), '', $this->original_path), '\\/');
        }
    }


    /**
     * @return null|string
     */
    public function get_original_path(): ?string {
        return $this->original_path;
    }


    /**
     * @return null|string
     * @aliase get_path_relative()
     */
    public function get_relative_path(): ?string {
        return $this->relative_path;
    }


    /**
     * @alias get_relative_path()
     * @return null|string
     */
    public function get_path_relative(): ?string {
        return $this->relative_path;
    }


    /**
     * @return null|string
     * @aliase get_path()
     */
    public function get_absolute_path(): ?string {
        return $this->absolute_path;
    }


    /**
     * @alias get_absolute_path()
     * @return string
     */
    public function get_path(): ?string {
        return $this->absolute_path;
    }


    /**
     * @return string|string[]
     */
    public function get_path_at_theme() {
        return ltrim(str_replace(str_replace('\\', '/', get_stylesheet_directory()), '', $this->get_absolute_path()), '/\\');
    }


    /**
     * Return file url
     * @param null $return_universalScheme
     * @return string
     */
    public function get_url($return_universalScheme = null): string {
        return $this->url()->get($return_universalScheme);
    }


    /**
     * @return bool|null
     */
    public function is_exists(): ?bool {
        if (is_string($this->path()->get_original_path()) && $this->path()->is_local()) {
            return file_exists($this->get_absolute_path());
        }
        return null;
    }


    /**
     * @return bool|null
     */
    public function is_readable(): ?bool {
        if ( !$this->path()->is_local()) return null;
        return file_exists($this->get_absolute_path()) && is_readable($this->get_absolute_path());
    }


    /**
     * @return bool
     */
    public function is_writable(): bool {
        if ( !$this->path()->is_local()) return false;
        return is_writable($this->path()->get_absolute_path());
    }


    /**
     * @param array  $fileExtension
     * @param string $excludeFiles_withPrefix
     * @param int    $depth - depth of sub dirs
     * @return Path_File[]
     */
    public function include_files($fileExtension = [ 'php', 'css', 'js' ], $excludeFiles_withPrefix = '-', $depth = 99): array {
        $R = [];
        if ( !$this->is_readable() || !$this->is_dir()) {
            ConsoleFactory::add('Dir is not readable or not exists', __METHOD__, [], true);
        } else {
            $subFiles = $this->get_sub_files($fileExtension, $depth);
            foreach ($subFiles as $file) {
                ///skip folders and files
                if ($file->get_next_file('.notinclude')->is_exists()) continue;
                if ( !$file->is_readable()) continue;
                if ($excludeFiles_withPrefix != '' && strpos($file->get_basename(), $excludeFiles_withPrefix) === 0) continue;
                ///
                switch($file->get_extension()) {
                    case 'php':
                        $path = $file->get_path();
                        include_once $path;
                        $R[$file->original_path] = $file;
                        break;
                    case 'css':
                        $path = $file->get_path();
                        IncludesFactory::css($path);
                        $R[$file->original_path] = $file;
                        break;
                    case 'js':
                        $path = $file->url()->get();
                        IncludesFactory::js($path);
                        $R[$file->original_path] = $file;
                        break;
                }
            }
        }

        return $R;
    }


    /**
     * @param array $needle_file_names
     * @param int   $depth
     * @return Path_File[]
     * @version 1.1
     */
    public function include_files_by_name($needle_file_names = [ 'functions.php' ], $depth = 999): array {
        $needle_file_names = (array)$needle_file_names;
        $dir = $this;
        $R = [];
        if ($dir->is_readable() && $dir->is_dir()) {
            foreach ($dir->get_sub_files([], $depth) as $file) {
                if (in_array($file->get_basename(), $needle_file_names) && $file->is_exists() && $file->is_file() && $file->is_readable()) {
                    include $file->get_absolute_path();
                }
            }
        }
        return $R;
    }


    /**
     * @return bool|null
     */
    public function is_dir(): ?bool {
        if ($this->path()->is_local()) return is_dir($this->get_path());
        return null;
    }


    /**
     * @return bool|null
     */
    public function is_file(): ?bool {
        if ($this->path()->is_local()) return is_file($this->get_path());
        return null;
    }


    /**
     * @return bool|null
     */
    public function is_link(): ?bool {
        if ($this->path()->is_local()) return is_link($this->get_path());
        return null;
    }


    /**
     * @return bool|null
     */
    public function is_uploaded_file(): ?bool {
        if ($this->path()->is_local()) return is_uploaded_file($this->get_path());
        return null;
    }


    /**
     * @return bool|null
     */
    public function is_executable(): ?bool {
        if ($this->path()->is_local()) return is_executable($this->get_path());
        return null;
    }


    /**
     * @return string|null
     * @deprecated use get_mime_content_type()
     */
    protected function mime(): ?string {
        return $this->get_mime_content_type();
    }


    /**
     * Return mime type of file if path is file
     * @return string|null
     */
    public function get_mime_content_type(): ?string {
        if ( !$this->is_file()) return null;
        return mime_content_type($this->get_path());
    }


    /**
     * @return string
     * @deprecated use get_extension()
     */
    protected function extension(): string {
        return $this->get_extension();
    }


    /**
     * @return string
     */
    public function get_extension(): string {
        $pathInfo = pathinfo($this->get_path_relative());
        return isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
    }


    /**
     * @return string
     * @deprecated use get_basename()
     */
    protected function basename(): string {
        return $this->get_basename();
    }


    /**
     * Return file name which extension, like 'filename.jpg'
     * @return string
     */
    public function get_basename(): string {
        return basename($this->original_path);
    }


    /**
     * @return string
     * @deprecated use get_filename()
     */
    protected function filename(): string {
        return $this->get_filename();
    }


    /**
     * Return file name whithout extension, like 'filename'
     * @return string
     */
    public function get_filename(): string {
        if ($this->get_extension() != '') {
            return substr($this->get_basename(), 0, strlen('.' . $this->get_extension()) * - 1);
        }
        return $this->get_basename();
    }


    /**
     * @return string
     * @deprecated use get_dirname()
     */
    protected function dirname(): string {
        return $this->get_dirname();
    }


    /**
     * Return dir name component of path
     * @return string
     */
    public function get_dirname(): string {
        return dirname($this->get_path());
    }


    /**
     * @return ArrayObject
     */
    public function dirs(): ArrayObject {
        return CacheFactory::get(spl_object_id($this->path()), __METHOD__, function() {
            return get_array(explode('/', func_get_arg(0)->dirname()));
        }, [ $this ])->get_value();
    }


    /**
     * Return true, if file is exists and image
     * @return bool
     */
    public function is_image(): bool {
        return ($this->is_file() && strpos($this->image()->get_mime_type(), 'image') === 0);
    }


    /**
     * @param null|string $default
     * @return string
     */
    public function get_content($default = null): ?string {
        //TODO: сделать чтение содержимого из удаленного URL файла
        if ($this->is_readable()) return file_get_contents($this->get_path()); else return $default;
    }


    /**
     * @param          $content
     * @param bool|int $appendPrepend - true|1 - добавить строку(и) в файл, -1 - препенд строки к файлу
     * @return bool|int
     * @version 1.1
     */
    public function set_content($content, $appendPrepend = false) {
        if ( !$this->path()->is_local()) return - 1;
        if ($this->is_exists() && !$this->is_writable()) return - 2;
        ///
        if ($appendPrepend === true || $appendPrepend > 0) {
            $content = (string)$this->get_content() . $content;
        } elseif ($appendPrepend < 0) {
            $content = $content . (string)$this->get_content();
        }
        return file_put_contents($this->get_path(), $content);
    }


    /**
     * @return bool|int
     * @version 1.1
     * Return file or directory size in bites
     */
    public function get_size() { //TODO~!!!
        $R = false;
        if ($this->is_file()) {
            return filesize($this->get_path());
        } elseif ($this->is_dir()) {
            $files = $this->get_sub_files();
            $size = 0;
            foreach ($files as $file) {
                $size += $file->get_size();
            }
            return $size;
        }
        return $R;
    }


    /**
     * @return string
     */
    public function get_size_formatted(): string {
        return PathsFactory::get_size_formatted($this->get_size());
    }


    /**
     * Возвращает массив вложенных файлов
     * @param array $extensionsFilter - маска файлов
     * @param int   $depth            - грлубина просмотра файлов
     * @return Path_File[]
     * @version 1.4
     */
    public function get_sub_files($extensionsFilter = [], $depth = 99): array {
        if ( !$this->path()->is_local() || $depth < 0) return [];
        ///
        $extensionsFilter = (array)$extensionsFilter;
        $maskKey = json_encode($extensionsFilter);
        $cache_key = $maskKey . 'depth:' . $depth;
        if ( !array_key_exists($cache_key, $this->cache_subFiles)) {
            $this->cache_subFiles[$cache_key] = [];
            if ($this->is_dir()) {
                $dir = opendir($this->get_absolute_path());
                while(false !== ($subFileName = readdir($dir))) {
                    if ($subFileName == '.' || $subFileName == '..') continue;
                    $subFilePath = $this->get_path() . '/' . $subFileName;
                    $subFile = PathsFactory::get($subFilePath)->file();
                    if ($subFile->is_dir()) {
                        $this->cache_subFiles[$cache_key] = array_merge($this->cache_subFiles[$cache_key], $subFile->get_sub_files($extensionsFilter, $depth - 1));
                    } else {
                        if (is_array($extensionsFilter) && count($extensionsFilter) > 0) {
                            if ( !in_array(PathsFactory::get_extension($subFileName), $extensionsFilter)) continue;
                        }
                        $this->cache_subFiles[$cache_key][$subFile->get_original_path()] = $subFile;
                    }
                }
            }
        }
        return $this->cache_subFiles[$cache_key];
    }


    /**
     * Return all sub files, include sub-folders
     * @param bool $returnOnlyPaths - return only string paths in array
     * @return Path_File[]|string[]
     */
    public function get_sub_files_by_mtime($returnOnlyPaths = false): array {
        $R = get_array();
        if ($this->is_dir()) {
            $dir = opendir($this->get_absolute_path());
            while(false !== ($subFileName = readdir($dir))) {
                if ($subFileName == '.' || $subFileName == '..') continue;
                $subFilePath = $this->get_path() . '/' . $subFileName;
                if (is_dir($subFilePath) && is_readable($subFilePath)) {
                    $R->push(PathsFactory::get($subFilePath)->file()->get_sub_files_by_mtime());
                } else {
                    $R->push($R->free_key(filemtime($subFilePath)), $returnOnlyPaths ? $subFilePath : PathsFactory::get($subFilePath)->file());
                }
            }
        }
        return $R->get();
    }


    /**
     * Get next file
     * @param $file_name
     * @return Path_File
     */
    public function get_next_file($file_name): Path_File {
        return PathsFactory::get($this->get_dirname() . '/' . $file_name)->file();
    }


    /**
     * @param string $content
     * @return bool|int
     */
    public function make_file($content = '') {
        if ( !$this->path()->is_local()) {
            if (function_exists('console_error')) {
                console_error('\hiweb\files\file::make: не удалось создать файл [' . $this->original_path . '], потому что ссылка не локальная');
            }
            return - 1;
        }
        if ($this->is_exists()) {
            if (function_exists('console_error')) {
                console_error('\hiweb\files\file::make: не удалось создать файл [' . $this->original_path . '], потому что он уже существует');
            }
            return - 2;
        }
        if ($this->is_writable()) {
            if (function_exists('console_error')) {
                console_error('\hiweb\files\file::make: не удалось создать файл [' . $this->original_path . '], потому что нет прав на запись');
            }
            return - 3;
        }

        if (file_put_contents($this->get_path(), $content)) {
            return true;
        } else {
            if (function_exists('console_error')) {
                console_error('\hiweb\files\file::make: не удалось создать файл [' . $this->original_path . ']');
            }
            return - 4;
        }
    }

}