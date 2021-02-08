<?php

namespace hiweb\components\Breadcrumb;


use hiweb\components\Structures\StructuresFactory;
use hiweb\core\hidden_methods;


/**
 * Class BreadcrumbFactory
 * @package theme\breadcrumb
 * @version 2.0
 */
class BreadcrumbFactory {

    static $admin_options_slug = 'hiweb-breadcrumbs';
    static private $breadcrumbs = [];
    /** @var Breadcrumb */
    static protected $current_breadcrumb;

    use hidden_methods;


    static function init() {
        include_frontend_css(__DIR__ . '/assets/breadcrumbs.css');
        include_once __DIR__ . '/options.php';
    }


    /**
     * @param null $contextObject
     * @return mixed|Breadcrumb
     */
    static function get($contextObject = null): Breadcrumb {
        $key = StructuresFactory::get_id_from_object($contextObject);
        if ( !array_key_exists($key, self::$breadcrumbs)) {
            self::$breadcrumbs[$key] = new Breadcrumb($contextObject);
            self::$current_breadcrumb = self::$breadcrumbs[$key];
        }
        return self::$breadcrumbs[$key];
    }


    /**
     * @return Breadcrumb
     */
    static function the(): Breadcrumb {
        return self::$current_breadcrumb;
    }

}