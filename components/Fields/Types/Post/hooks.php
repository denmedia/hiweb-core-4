<?php

use hiweb\components\Fields\Types\Post\Field_Post;


if (function_exists('add_action')) {

    add_action('wp_ajax_hiweb-components-fields-type-post', function() {
        /** @var Field_Post $Field */
        $Field = \hiweb\components\Fields\FieldsFactory::get_field($_POST['global_id']);
        $query_post_types = '';
        if ($Field instanceof Field_Post) {
            $query_post_types = $Field->options()->post_type();
        } elseif (isset($_POST['post_type'])) {
            $query_post_types = $_POST['post_type'];
        }
        if ($query_post_types == '') {
            $query_post_types = [ 'post', 'page' ];
        }
        $query = [
            'post_type' => $query_post_types,
            //'wpse18703_title' => $_POST['search'],
            'posts_per_page' => 99,
            'post_status' => 'any',
            's' => $_POST['search'],
            'orderby' => 'title',
            'order' => 'ASC'
        ];
        $wp_query = new WP_Query($query);
        $R = [];

        $is_many_post_types = (is_array($Field->options()->post_type()) && count($Field->options()->post_type()) > 1);
        $post_type_names = [];
        if ($is_many_post_types) {
            foreach (get_post_types([], OBJECT) as $WP_Post_Type) {
                $post_type_names[$WP_Post_Type->name] = $WP_Post_Type->label;
            }
        }

        /** @var WP_Post $wp_post */
        foreach ($wp_query->get_posts() as $wp_post) {
            $R[] = [
                'value' => $wp_post->ID,
                'title' => $wp_post->post_title == '' ? '--без названия: ' . $wp_post->ID . '--' : (($is_many_post_types && array_key_exists($wp_post->post_type, $post_type_names)) ? $post_type_names[$wp_post->post_type] . ': ' : '') . $wp_post->post_title,
                //'name' => '<img src="' . get_image( get_post_thumbnail_id( $wp_post ) )->get_src( 'thumbnail' ) . '">' . $wp_post->post_title
            ];
        }

        //wp_send_json_success( $R );
        echo json_encode([
            'success' => true,
            'items' => $R
        ]);
        die;
    });
}