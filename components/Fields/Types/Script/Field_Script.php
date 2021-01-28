<?php

namespace hiweb\components\Fields\Types\Script;


use hiweb\components\Fields\Field;


class Field_Script extends Field {


    public function get_css(): ?array {
        return [
            HIWEB_DIR_VENDOR . '/codemirror/codemirror.css',
            HIWEB_DIR_VENDOR . '/codemirror/addon/hint/show-hint.css',
            HIWEB_DIR_VENDOR . '/codemirror/addon/fold/foldgutter.css',
            __DIR__ . '/assets/script.css'
        ];
    }


    public function get_js(): ?array {
        return [
            HIWEB_DIR_VENDOR . '/codemirror/codemirror.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/hint/show-hint.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/hint/javascript-hint.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/hint/css-hint.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/hint/xml-hint.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/hint/html-hint.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/hint/anyword-hint.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/selection/selection-pointer.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/edit/matchbrackets.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/comment/continuecomment.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/comment/comment.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/fold/foldcode.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/fold/foldgutter.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/fold/brace-fold.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/fold/xml-fold.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/fold/indent-fold.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/fold/markdown-fold.js',
            HIWEB_DIR_VENDOR . '/codemirror/addon/fold/comment-fold.js',
            HIWEB_DIR_VENDOR . '/codemirror/mode/xml.js',
            HIWEB_DIR_VENDOR . '/codemirror/mode/htmlmixed.js',
            HIWEB_DIR_VENDOR . '/codemirror/mode/javascript.js',
            __DIR__ . '/assets/script.min.js'
        ];
    }


    public function get_admin_html($value = null, $name = null): string {
        ob_start();
        include __DIR__ . '/template.php';
        return ob_get_clean();
    }

}