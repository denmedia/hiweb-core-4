<?php

///init components
foreach (\hiweb\components\ComponentsManager\ComponentsManager::get_components() as $component) {
    if ($component->is_enable()) $component->init();
}