<?php
if ( !isset($args) || !$args instanceof \hiweb\components\Breadcrumb\Breadcrumb) return;

use hiweb\components\Breadcrumb\BreadcrumbFactory;


?>
<li class="<?= $args->get_class_item(true) ?>"><?php

    if (get_field('home-href-enable', BreadcrumbFactory::$admin_options_slug)) {
    $url = get_field('home-href', BreadcrumbFactory::$admin_options_slug);
    $url = $url == '' ? get_home_url() : $url;
    ?><a href="<?= esc_url($url) ?>"><?php
        }

        if (get_field('home-icon-enable', BreadcrumbFactory::$admin_options_slug) && get_field('home-icon', BreadcrumbFactory::$admin_options_slug) != '') {
            echo get_fontawesome(get_field('home-icon', BreadcrumbFactory::$admin_options_slug));
        }
        if (get_field('home-label-enable', BreadcrumbFactory::$admin_options_slug) && (get_field('home-label-enable-mobile', BreadcrumbFactory::$admin_options_slug) || !get_client()->is_mobile())) {
            $label = get_field('home-label', BreadcrumbFactory::$admin_options_slug);
            $label = $label == '' ? get_bloginfo('name') : $label;
            echo $label;
        }

        if (get_field('home-href-enable', BreadcrumbFactory::$admin_options_slug)) {
        ?></a><?php
}

?></li>