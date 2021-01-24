<?php
if ( !function_exists('debug_console_start')) {
    function debug_console_start($testId = 'Debug microtime') {
        \hiweb\core\Debug\Debug::microtime_start($testId);
    }
}

if ( !function_exists('debug_console_point')) {
    function debug_console_point($description = '', $testId = 'Debug microtime', $min = .005, $round = 1000) {
        \hiweb\core\Debug\Debug::microtime_console($description, $testId, $min, $round);
    }
}