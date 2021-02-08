<?php

use hiweb\components\Breadcrumb\BreadcrumbFactory;


if ( !function_exists('the_breadcrumbs')) {

    /**
     * @param $contextObject
     */
    function the_breadcrumbs($contextObject = null) {
        BreadcrumbFactory::get($contextObject)->the_html();
    }
}