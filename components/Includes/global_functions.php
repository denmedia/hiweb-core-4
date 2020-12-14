<?php

use hiweb\components\Includes\IncludesFactory_AdminPage;
use hiweb\components\Includes\Css;
use hiweb\components\Includes\IncludesFactory_FrontendPage;
use hiweb\components\Includes\IncludesFactory;
use hiweb\components\Includes\IncludesFactory_LoginPage;
use hiweb\components\Includes\Js;

if ( !function_exists('include_js')) {

    /**
     * Include JS file everywhere
     * @param       $fileNameOrPathOrURL
     * @param array $deeps
     * @param bool  $set_toFooter
     * @return Js
     */
    function include_js($fileNameOrPathOrURL, $deeps = [], $set_toFooter = true): Js {
        $Js = IncludesFactory::js($fileNameOrPathOrURL);
        $Js->deeps($deeps);
        $Js->to_footer($set_toFooter);
        $Js->defer(true);
        return $Js;
    }
}

if ( !function_exists('include_frontend_js')) {
    /**
     * Include JS file only on frontend pages
     * @param       $fileNameOrPathOrURL
     * @param array $deeps
     * @param bool  $set_toFooter
     * @return Js
     */
    function include_frontend_js($fileNameOrPathOrURL, $deeps = [], $set_toFooter = true): Js {
        $Js = IncludesFactory_FrontendPage::js($fileNameOrPathOrURL);
        $Js->deeps($deeps);
        $Js->to_footer($set_toFooter);
        $Js->defer(true);
        return $Js;
    }
}

if ( !function_exists('include_admin_js')) {
    /**
     * Include JS file only on admin pages
     * @param       $fileNameOrPathOrURL
     * @param array $deeps
     * @param bool  $set_toFooter
     * @return Js
     */
    function include_admin_js($fileNameOrPathOrURL, $deeps = [], $set_toFooter = true): Js {
        $Js = IncludesFactory_AdminPage::js($fileNameOrPathOrURL);
        $Js->deeps($deeps);
        $Js->to_footer($set_toFooter);
        $Js->defer(true);
        return $Js;
    }
}

if ( !function_exists('include_login_js')) {
    /**
     * Include JS file only on login page
     * @param       $fileNameOrPathOrURL
     * @param array $deeps
     * @param bool  $set_toFooter
     * @return Js
     */
    function include_login_js($fileNameOrPathOrURL, $deeps = [], $set_toFooter = true): Js {
        $Js = \hiweb\components\Includes\IncludesFactory_LoginPage::js($fileNameOrPathOrURL);
        $Js->deeps($deeps);
        $Js->to_footer($set_toFooter);
        $Js->defer(true);
        return $Js;
    }
}

if ( !function_exists('include_css')) {
    /**
     * @param       $fileNameOrPathOrURL
     * @param array $deeps
     * @param bool  $set_toFooter
     * @return Css
     */
    function include_css($fileNameOrPathOrURL, $deeps = [], $set_toFooter = false): Css {
        $Css = IncludesFactory::css($fileNameOrPathOrURL);
        $Css->deeps($deeps);
        $Css->to_footer($set_toFooter);
        return $Css;
    }
}

if ( !function_exists('include_frontend_css')) {
    /**
     * @param       $fileNameOrPathOrURL
     * @param array $deeps
     * @param bool  $set_toFooter
     * @return Css
     */
    function include_frontend_css($fileNameOrPathOrURL, $deeps = [], $set_toFooter = false): Css {
        $Css = IncludesFactory_FrontendPage::css($fileNameOrPathOrURL);
        $Css->deeps($deeps);
        $Css->to_footer($set_toFooter);
        return $Css;
    }
}

if ( !function_exists('include_admin_css')) {
    /**
     * @param       $fileNameOrPathOrURL
     * @param array $deeps
     * @param bool  $set_toFooter
     * @return Css
     */
    function include_admin_css($fileNameOrPathOrURL, $deeps = [], $set_toFooter = false): Css {
        $Css = IncludesFactory_AdminPage::css($fileNameOrPathOrURL);
        $Css->deeps($deeps);
        $Css->to_footer($set_toFooter);
        return $Css;
    }
}

if ( !function_exists('include_login_css')) {
    /**
     * Include CSS file only on login page
     * @param       $fileNameOrPathOrURL
     * @param array $deeps
     * @param bool  $set_toFooter
     * @return Css
     */
    function include_login_css($fileNameOrPathOrURL, $deeps = [], $set_toFooter = false): Css {
        $Css = IncludesFactory_AdminPage::css($fileNameOrPathOrURL);
        $Css->deeps($deeps);
        $Css->to_footer($set_toFooter);
        return $Css;
    }
}

if ( !function_exists('include_scripts')) {

    /**
     * Include vendor scripts on frontend page
     * Return instance of IncludesFactory_FrontendPage
     * @return IncludesFactory
     */
    function include_scripts(): IncludesFactory {
        static $class;
        if ( !$class instanceof IncludesFactory) $class = new IncludesFactory();
        return $class;
    }
}

if ( !function_exists('include_frontend')) {

    /**
     * Include vendor scripts on frontend page
     * Return instance of IncludesFactory_FrontendPage
     * @return IncludesFactory_FrontendPage
     */
    function include_frontend(): IncludesFactory_FrontendPage {
        static $class;
        if ( !$class instanceof IncludesFactory_FrontendPage) $class = new IncludesFactory_FrontendPage();
        return $class;
    }
}

if ( !function_exists('include_admin')) {

    /**
     * Include vendor scripts on admin page
     * Return instance of IncludesFactory_FrontendPage
     * @return IncludesFactory_AdminPage
     */
    function include_admin(): IncludesFactory_AdminPage {
        static $class;
        if ( !$class instanceof IncludesFactory_AdminPage) $class = new IncludesFactory_AdminPage();
        return $class;
    }
}

if ( !function_exists('include_login')) {

    /**
     * Include vendor scripts on login page
     * Return instance of IncludesFactory_FrontendPage
     * @return IncludesFactory_LoginPage
     */
    function include_login(): IncludesFactory_LoginPage {
        static $class;
        if ( !$class instanceof IncludesFactory_LoginPage) $class = new IncludesFactory_LoginPage();
        return $class;
    }
}