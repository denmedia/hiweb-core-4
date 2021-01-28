<?php

register_hiweb_component('\hiweb\components\RewriteSlugs\RewriteSlugs::init', 'Rewrite Slugs', sprintf(__('Rewrite base slug for post type archive page, base custom post and taxonomies. For setup, go to %s', 'hiweb-core-4'), '<a href="' . get_admin_url(null, 'options-permalink.php') . '">Permalinks</a>'));