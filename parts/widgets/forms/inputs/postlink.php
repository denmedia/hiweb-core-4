<?php

	$input = get_the_form()->get_the_input();

?>
<div data-ajax-html="<?= htmlentities( json_encode( [ 'form_id' => get_the_form_id(), 'input_id' => $input->get_data( 'name' ) ] ) ) ?>"><?php

		$input->the_prefix();
		$value = '';
		if( $input->get_data( 'show' ) ){
			$wp_post = get_post( $input->get_data( 'value' ) != '' ? $input->get_data( 'value' ) : get_the_ID() );
			if( $wp_post instanceof \WP_Post ){
				$value = $wp_post->ID;
				?>
				<div class="post-thumbnail"><?= get_image( get_post_thumbnail_id( $wp_post ) )->html( [ 200, 200 ] ) ?></div>
				<div class="post-title"><?= $wp_post->post_title ?></div>
				<?php
			}
		}
	?><input type="hidden" name="<?= $input->get_name() ?>" value="<?= $value ?>"/>
	<?php
		$input->the_sufix();
	?>
</div>