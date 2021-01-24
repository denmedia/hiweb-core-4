<?php

namespace hiweb\core\Debug;


/**
 * Core Object
 */
class Debug {

    static $microtimes = [];


    static function microtime_start($testId = 'Debug microtime') {
        self::$microtimes[$testId] = microtime(true);
    }


    static function microtime_console($description = '', $testId = 'Debug microtime', $min = .0005, $round = 10000) {
        $result = (round((microtime(true) - self::$microtimes[$testId]) * $round) / $round);
        if ($result > $min) {
            console_info($result . ' → ' . $description, $testId);
        }
        self::microtime_start($testId);
    }

}
