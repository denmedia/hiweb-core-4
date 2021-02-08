<?php

namespace hiweb\components\Console;


use hiweb\components\Context;


/**
 * Class ConsoleFactory
 * @package hiweb\components\Console
 * @version 1.2
 */
class ConsoleFactory {


    /** @var array|Console[] */
    static $messages = [];
    static $messages_limit = 512;
    static $id_count = 0;


    /**
     * @param string $content
     * @param string $type
     * @param string $groupTitle
     * @param array  $additionData
     * @param bool   $debugStatus
     * @return Console
     * @version 1.1
     */
    static function add($content = '', $type = 'info', $groupTitle = '', $additionData = [], $debugStatus = false): Console {
        $console = new Console($content, $type, $additionData);
        $console->set_groupTitle($groupTitle);
        if ($debugStatus) {
            $console->set_debugStatus(true);
            $console->addition_data = array_merge((array)$console->addition_data, [ 'debug_backtrace' => debug_backtrace() ]);
        }
        self::$messages[$groupTitle][self::$id_count] = $console;
        self::$id_count ++;
        return $console;
    }


    /**
     * @return string
     * @version 1.0
     */
    static function get_html(): string {
        if (count(self::$messages) === 0) return '';
        $html = '<script>/** hiWeb console block **/';
        while(self::$messages_limit > 0 && count(self::$messages) > 0) {
            $groupTitle = key(self::$messages);
            $messages = array_shift(self::$messages);
            ///
            if ($groupTitle != '') {
                $html .= 'console.groupCollapsed("%c' . addslashes($groupTitle) . '", "color: #888;font-size: 1.2em;");';
            }
            ///
            while(self::$messages_limit > 0 && count($messages) > 0) {
                $message = array_shift($messages);
                if ($message instanceof Console) $html .= $message->html(false);
                self::$messages_limit --;
            }
            ///
            if ($groupTitle != '') {
                $html .= 'console.groupEnd();';
            }
        }
        $html .= '</script>';
        return $html;
    }


    /**
     * Print messages script
     * @version 1.5
     */
    static function the() {
        if(!Context::is_frontend_page() && !Context::is_admin_page() && !Context::is_login_page()) return;
        echo self::get_html();
    }
}
