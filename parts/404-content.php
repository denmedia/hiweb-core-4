<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 23/10/2018
	 * Time: 11:33
	 */

	theme\error_404::init();

?>
<main class="page-404">
	<div class="container">
		<h1><?= get_field( 'title', 'error-404' ) ?></h1>
		<?= get_field_content( 'content', 'error-404' ) ?>
	</div>
</main>
