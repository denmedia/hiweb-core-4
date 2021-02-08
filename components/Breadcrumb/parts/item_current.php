<?php

use hiweb\components\Breadcrumb\BreadcrumbFactory;


if ( !isset($args) || !$args instanceof \hiweb\components\Breadcrumb\Breadcrumb_Item) return;
?>
<li class="<?= $args->get_breadcrumb()->get_class_item(true) ?>"><?php if (get_field('current-enable-href', BreadcrumbFactory::$admin_options_slug)) {
    ?><a href="<?= $args->get_url() ?>"><?php
        } ?><?= $args->get_title() ?><?php if (get_field('current-enable-href', BreadcrumbFactory::$admin_options_slug)) {
        ?></a><?php
} ?></li>