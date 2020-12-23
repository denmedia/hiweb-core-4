<?php

namespace hiweb\components\Dates;


class DatesFactory {

    static function get_rdate($format, $timestamp = null, $case = 0) {
        if ($timestamp === null) $timestamp = time();

        static $loc = 'январ,ь,я,е,ю,ём,е
	феврал,ь,я,е,ю,ём,е
	март, ,а,е,у,ом,е
	апрел,ь,я,е,ю,ем,е
	ма,й,я,е,ю,ем,е
	июн,ь,я,е,ю,ем,е
	июл,ь,я,е,ю,ем,е
	август, ,а,е,у,ом,е
	сентябр,ь,я,е,ю,ём,е
	октябр,ь,я,е,ю,ём,е
	ноябр,ь,я,е,ю,ём,е
	декабр,ь,я,е,ю,ём,е';

        if (is_string($loc)) {
            $months = array_map('trim', explode("\n", $loc));
            $loc = [];
            foreach ($months as $monthLocale) {
                $cases = explode(',', $monthLocale);
                $base = array_shift($cases);

                $cases = array_map('trim', $cases);

                $loc[] = [
                    'base' => $base,
                    'cases' => $cases,
                ];
            }
        }

        $m = (int)date('n', $timestamp) - 1;

        $F = $loc[$m]['base'] . $loc[$m]['cases'][$case];

        $format = strtr($format, [
            'F' => $F,
            'M' => substr($F, 0, 3),
        ]);

        return date($format, $timestamp);
    }

}