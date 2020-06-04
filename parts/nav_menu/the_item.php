<?php


	$active = \hiweb\components\Structures\StructuresFactory::get()->has_children_object( theme\nav_menu::the_item() ) || get_url()->is_dirs_intersect( theme\nav_menu::the_item()->url );
?>
<li class="<?= implode( ' ', theme\nav_menu::the_instance()->item_classes ) ?><?= $active ? ' ' . theme\nav_menu::the_instance()->item_class_active : '' ?>">
	<?php
		if( theme\nav_menu::the_item()->url == '#' ){
			?>
			<a class="<?= implode( ' ', theme\nav_menu::the_instance()->link_classes ) ?><?= $active ? ' ' . theme\nav_menu::the_instance()->item_class_active : '' ?>"><?= theme\nav_menu::the_item()->title ?></a>
			<?php
		} else {
			?>
			<a class="<?= implode( ' ', theme\nav_menu::the_instance()->link_classes ) ?><?= $active ? ' ' . theme\nav_menu::the_instance()->item_class_active : '' ?>" href="<?= theme\nav_menu::the_item()->url ?>"><?= theme\nav_menu::the_item()->title ?></a>
			<?php
		}
	?>
	<?php theme\nav_menu::the_instance()->the_list( theme\nav_menu::the_item()->ID, theme\nav_menu::the_item_depth() + 1 ); ?>
</li>