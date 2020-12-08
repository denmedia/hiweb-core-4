<?php
if (function_exists('add_action')) {
    add_action('rest_api_init', function() {

        register_rest_route('hiweb/components', '/fontawesome/svg/(?P<icon>(fa(?>b|l|s|r|d)(%20)fa-)?[\w\-\_]+)', [
            'methods' => 'GET',
            'callback' => function(WP_REST_Request $request) {
                header('Content-type: image/svg+xml');
                $icon = \hiweb\components\FontAwesome\FontAwesomeFactory::get(urldecode($request->get_param('icon')));
                if ($icon->is_exists()) {
                    echo $icon->get_style(isset($_GET['style']) ? $_GET['style'] : '')->get_raw();
                } else {
                    $icons = \hiweb\components\FontAwesome\FontAwesomeFactory::get_search_icons($request->get_param('icon'));
                    if (count($icons) > 0) {
                        echo current($icons)->get_style(isset($_GET['style']) ? $_GET['style'] : '')->get_raw();
                    } else {
                        echo (new \hiweb\components\FontAwesome\FontAwesome_Icon('question-circle'))->get_style(isset($_GET['style']) ? $_GET['style'] : '')->get_raw();
                    }
                }
                die;
            }
        ]);

        register_rest_route('hiweb/components', '/fontawesome/search/(?P<query>[\w\-\_]+)', [
            'methods' => 'GET',
            'callback' => function(WP_REST_Request $request) {
                $R = [];
                foreach (\hiweb\components\FontAwesome\FontAwesomeFactory::get_search_icons($request->get_param('query')) as $id => $Icon) {
                    $R[$id] = [
                        'label' => $Icon->get_label(),
                        'class' => $Icon->get_class(),
                        'svg' => $Icon->get_style()->get_raw()
                    ];
                }
                return $R;
            }
        ]);
    });
}