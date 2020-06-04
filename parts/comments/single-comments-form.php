<?php

	///

?>
<div class="single-comments-form">
	<div class="comment-form-title"><?=get_field('form-title','comments')?></div>
	<?php
		if( get_field( 'form-description', 'comments' ) != '' ){
			?>
			<div class="description"><?= get_field_content( 'form-description', 'comments' ) ?></div>
			<?php
		}
	?>
	<div class="row align-items-start">
		<div class="col flex-grow-0">
			<div class="form-avatar">
				<?= get_image( get_field( 'default-avatar', 'news' ) )->html( [ 64, 64 ], 0, [ 'class' => 'comment-avatar' ] ); ?>
			</div>
		</div>
		<div class="form-wrap col flex-grow-1" data-status="">
			<form method="post" id="comments-form" action="<?= rest_url( 'zorbasmedia/add-comment' ) ?>">
				<input type="hidden" name="post_id" value="<?= get_the_ID() ?>">
				<input type="hidden" name="comment_parent" value="0">
				<div class="input-wrap input-wrap-name">
					<input name="name" placeholder="<?= htmlentities( get_field( 'form-placeholder-name', 'comments' ) ) ?>" required>
				</div>
				<div class="input-wrap input-comment-reply" data-reply-id="0">
					<div class="info-wrap">
						Ответ на комментарий: <b data-comment-answer-name>....</b> <a href="#" data-click="comment-reply-disable"><i class="fas fa-times-circle"></i></a>
					</div>
				</div>
				<div class="input-wrap input-wrap-text">
					<textarea rows="8" name="text" placeholder="<?= htmlentities( get_field( 'form-placeholder-text', 'comments' ) ) ?>" required></textarea>
				</div>
				<div class="input-wrap input-submit">
					<button type="submit"><?= get_field( 'form-submit-text', 'comments' ) ?></button>
				</div>
				<?php \hiweb_theme\widgets\forms\recaptcha::the_input() ?>
			</form>
			<div class="messages">
				<div class="wait"><i class="far fa-spin fa-circle-notch"></i></div>
				<div class="success"><i class="far fa-check-circle"></i></div>
				<div class="error"><i class="far fa-times-circle"></i></div>
			</div>
		</div>
	</div>
</div>