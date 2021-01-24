<?php

namespace hiweb\components\Fields\Types\Post;


use hiweb\components\Fields\Field_Options;


class Field_Post_Options extends Field_Options {

    public function post_type($set = null) {
        if (is_string($set)) $set = [ $set ];
        return $this->_('post_type', $set, [ 'post' ]);
    }


    public function placeholder($set = null) {
        return $this->_('placeholder', $set, $this->multiple() ? __('Select posts') : __('Select post'));
    }


    public function meta_key($set = null) {
        return $this->_('meta_key', $set);
    }


    public function multiple($set = null) {
        return $this->_('multiple', $set);
    }

}