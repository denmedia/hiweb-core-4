<?php

	use theme\scrolltop;


	$icon_class = get_field( 'icon', scrolltop::$admin_menu_slug );
	$scroll_speed = (int)get_field( 'scroll-speed', scrolltop::$admin_menu_slug );
	$fade_speed = (int)get_field( 'fade-speed', scrolltop::$admin_menu_slug );
	$scroll_offset = (int)get_field( 'scroll-offset', scrolltop::$admin_menu_slug );
	$target_selector = get_field( 'target_selector', scrolltop::$admin_menu_slug );
	$image = get_image( get_field( 'image', scrolltop::$admin_menu_slug ) );
	$text = get_field( 'text', scrolltop::$admin_menu_slug );
?>
<div class="<?= scrolltop::get_class() ?>" data-scroll-speed="<?= $scroll_speed ?>" data-fade-speed="<?= $fade_speed ?>" data-scroll-offset="<?= $scroll_offset ?>" data-target="<?= $target_selector ?>">
	<?php
		if( $image->is_attachment_exists() ){
			?>
			<div class="hiweb-theme-widget-scrolltop-image-wrap">
				<?= $image->html( [ 80, 80 ] ) ?>
			</div>
			<?php
		} else {
			?>
			<div class="hiweb-theme-widget-scrolltop-icon-wrap">
				<span class="<?= $icon_class ?>"></span>
			</div>
			<?php
		}
		if( $text != '' ){
			?>
			<div class="hiweb-theme-widget-scrolltop-text-wrap"><?= $text ?></div>
			<?php
		}
	?>
</div>