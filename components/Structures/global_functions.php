<?php

use hiweb\components\Structures\Structure;


if ( !function_exists('get_structure')) {
    /**
     * @param null|WP_Post|WP_Term|WP_User $wp_object
     * @return Structure
     */
    function get_structure($wp_object = null) {
        return \hiweb\components\Structures\StructuresFactory::get($wp_object);
    }
}