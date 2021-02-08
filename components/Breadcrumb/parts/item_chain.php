<?php

use hiweb\components\Breadcrumb\Breadcrumb_Item;


if ( !isset($args) || !($args instanceof Breadcrumb_Item)) return;
?>
<li class="<?= $args->get_breadcrumb()->get_class_item(true) ?>"><a href="<?= $args->get_url() ?>"><?= $args->get_title() ?></a></li>