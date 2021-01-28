<?php
	
	$ajax_tags = get_array( [
		'data-fields-query-id' => md5( json_encode( $field_query ) ),
		'data-fields-query' => $field_query,
		//'data-scripts-done' => wp_scripts()->done,
		'data-form-options' => $form_options,
        'data-form-loaded' => '0'
	] );

?>
<div class="hiweb-components-fields-form-wrap hiweb-components-fields-form-ajax-wrap preloading" <?= $ajax_tags->get_as_tag_attributes() ?>>
	<div class="hiweb-components-form-ajax-inner"></div>
</div>