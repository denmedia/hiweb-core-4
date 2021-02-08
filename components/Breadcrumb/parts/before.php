<?php

use hiweb\components\Breadcrumb\Breadcrumb;


if(!$args instanceof Breadcrumb) return;
?>
<nav aria-label="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList"><ol class="<?=$args->get_class_wrap(true)?>">