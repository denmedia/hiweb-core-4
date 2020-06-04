<?php

	use hiweb_theme\tools\languages;


	function comments_get_all_comments( $parent = 0, $depth = null ){
		static $depth_limit = 10;
		if( !is_int( $depth ) )
			$depth = $depth_limit;
		if( $depth < 1 )
			return [];
		$R = [];
		$comments_query = [
			'post_id' => get_the_ID(),
			'parent' => $parent,
			'status' => 'approve'
		];
		$comments = get_comments( $comments_query );
		/** @var WP_Comment $comment */
		foreach( $comments as $comment ){
			$bid = str_pad( '', $depth_limit - $depth, '-' ) . $comment->comment_ID . '!';
			$R[ $bid ] = $comment;
			$R = array_merge( $R, comments_get_all_comments( $comment->comment_ID, $depth - 1 ) );
		}
		return $R;
	}

?>
<div class="single-comments-wrap">
	<div class="single-comments">
		<div class="title"><?= get_field( 'title' ) ?></div>
		<?php
			$comments_by_page = array_chunk( comments_get_all_comments(), 10, true );
			if( \hiweb\arrays::is_empty( $comments_by_page ) ){
				?>
				<div class="comments-empty">
					<?= get_field( 'text-empty', 'comments' ) ?>
				</div>
				<?php
			} else {
				?>
				<div class="comments-list">
					<?php
						foreach( $comments_by_page as $page_index => $comments ){
							?>
							<div class="comments-page" data-page="<?= $page_index + 1 ?>">
								<?php

									foreach( $comments as $comment_bid => $comment ){
										/** @var WP_Comment $comment */
										$is_sub_comment = strpos( $comment_bid, '-' ) === 0;
										?>
										<div class="<?= $is_sub_comment ? 'comment-answer' : 'comment' ?>" data-comment-id="<?= $comment->comment_ID ?>">
											<div class="comment-avatar">
												<?php
													$avatar_ids = [ intval( get_field( 'avatar', $comment ) ), get_field( 'default-avatar', 'comments' ) ];
													foreach( $avatar_ids as $avatar_id ){
														$avatar_image = get_image( $avatar_id );
														if( $avatar_image->is_attachment_exists() ){
															echo $avatar_image->html( [ 100, 100 ], 0, [ 'class' => 'comment-avatar' ] );
															break;
														}
													}

												?>
											</div>
											<div class="content">
												<div class="meta-prefix d-flex align-items-center">
													<div class="name flex-fill">
														<?= $comment->comment_author ?>
													</div>
													<div class="date">
														<?= date_i18n( 'd F, h:i', strtotime( $comment->comment_date ) ) ?>
													</div>
													<div class="like ml-3">
														<div class="loading">
															<i class="fa fa-heart pulse"></i>
														</div>
														<a href="#" data-click="dislike">
															<i class="fa fa-thumbs-up"></i>
														</a>
														<span class="count" data-like-count><?= intval( get_comment_meta( $comment->comment_ID, 'zorbasmedia-likes-count', true ) ) ?></span>
														<a href="#" data-click="like">
															<i class="fa fa-thumbs-up"></i>
														</a>
													</div>
												</div>
												<div class="text">
													<?= $comment->comment_content ?>
												</div>
												<?php if( !$is_sub_comment ){
													?>
													<div class="meta-sufix">
														<a href="#" data-click="comment-reply" class="comment-reply">Ответить <i class="fa fa-reply"></i></a>
													</div>
													<?php
												} ?>
											</div>
										</div>
										<?php

									}
								?>
							</div>
							<?php
						}

					?>
				</div>
				<div class="paginate-wrap">
					<div class="navigation pagination comments-pagination"></div>
				</div>
				<?php
			}
		?>

	</div>
</div>
