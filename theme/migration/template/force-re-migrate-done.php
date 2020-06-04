<?php

	/** @var array $R */

	use theme\migration\includes\tools; ?>
<h1>Force Re-Migrate Done!</h1>
<?php if( is_array( $R ) ){
//	foreach( $R as $query => $count ){
//		?><!--<p><b>--><?php //echo $query ?><!--</b>: --><?php //echo $count ?><!--</p>--><?php
//	}

} ?>
<p><?=count($R)?> addresses have been updated</p>
<a class="button button-primary" href="<?php tools::the_request_url(); ?>">OK</a>