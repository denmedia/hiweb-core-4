<?php use theme\html_layout\tags\head; ?>
<head>
	<?php if( head::$use_wp_title ){ ?><title><?= wp_title() ?></title><?php } ?>
	<?php
		if( is_array( head::$html_addition ) && count( head::$html_addition ) > 0 ){
			echo head::get_htmlAddition( false );
		}
		if( head::$use_wp_head ){
			wp_head();
		}
	?>
</head>