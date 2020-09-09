<?php
	
	add_action('admin_head', function(){
		?><style type="text/css" id="hiweb-components-adminnotices-inline-styles">
			<?= hiweb\components\AdminNotices\AdminNotices_Factory::$selectors; ?> { display: none !important; }
		</style><?php
	});
	
	add_action( 'admin_notices', function(){
		?><div id="hiweb-components-adminnotices-wrap" data-selectors="<?= htmlentities( hiweb\components\AdminNotices\AdminNotices_Factory::$selectors ) ?>"></div><?php
	}, -999999 );