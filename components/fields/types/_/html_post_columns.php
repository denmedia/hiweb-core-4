<?php
	/**
	 * @var Field   $this
	 * @var integer $post_id
	 */

	use hiweb\components\fields\Field;


	echo get_post_meta( $post_id, $this->get_id(), true );