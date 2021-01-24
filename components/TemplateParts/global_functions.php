<?php

if ( !function_exists('hw_template_part')) {

    /**
     * @param string      $slug
     * @param null|string $name
     * @param array       $args
     */
    function hw_template_part(string $slug, $name = null, $args = []) {
        ///find parts
        if (get_template_part($slug, $name, $args) === false && function_exists('debug_backtrace')) {
            foreach (debug_backtrace() as $location) {
                $location = (object)$location;
                if ($location->file != '') {
                    if ((defined('HIWEB_DIR') && strpos($location->file, HIWEB_DIR) === 0) || defined('HIWEB_THEME_DIR') && strpos($location->file, HIWEB_THEME_DIR) === 0) {
                        $directory = str_replace('\\', '/', dirname($location->file));
                        $templates = [ $directory . '/' . $slug . '.php' ];
                        if ($name != '') {
                            $templates = [ $directory . '/' . $slug . '-' . $name . '.php' ];
                            $templates[] = $directory . '/parts/' . basename($slug) . '-' . $name . '.php';
                            if (strpos($slug, '/') !== false) {
                                $templates[] = $directory . '/parts/' . basename(dirname($slug)) . '/' . basename($slug) . '-' . $name . '.php';
                            }
                        }
                        $templates[] = $directory . '/parts/' . basename($slug) . '.php';
                        if (strpos($slug, '/') !== false) {
                            $templates[] = $directory . '/parts/' . basename(dirname($slug)) . '/' . basename($slug) . '.php';
                        }
                        $templates = array_unique($templates);
                        foreach ($templates as $path) {
                            if (file_exists($path)) {
                                require $path;
                                break 2;
                            }
                        }
                    }
                }
            }
        }
    }
}