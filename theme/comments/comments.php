<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 19/10/2018
	 * Time: 15:04
	 */
	
	add_admin_menu_page( 'comments', '<i class="fas fa-cog"></i> Опции', 'edit-comments.php' );
	
	add_field_text( 'title' )->VALUE( 'Отзывы' )->get_parent_field()->label( 'Заголовок перед комментариями' )->location()->ADMIN_MENUS( 'comments' );
	add_field_text( 'text-empty' )->VALUE( 'Нет записей' )->get_parent_field()->label( 'Текст, если нет отзывов' )->location()->ADMIN_MENUS( 'comments' );
	add_field_image( 'default-avatar' )->label( 'Стандартная аватарка' )->description( 'Эта аватарка будет установлена для каждого отзыва, если для такого не была установлена индивидуальная аватарка.' )->location()->ADMIN_MENUS( 'comments' );
	add_field_text( 'form-title' )->label( 'Заголовок формы' )->VALUE( 'Заполните форму' )->get_parent_field()->location( true );
	add_field_content( 'form-description' )->label( 'Комментарий к форме' )->location()->ADMIN_MENUS( 'comments' );
	add_field_text( 'form-placeholder-name' )->label( 'Плейсхолдер "Ваше имя"' )->VALUE( 'Ваше имя' )->get_parent_field()->location( true );
	add_field_text( 'form-placeholder-text' )->label( 'Плейсхолдер "Ваш отзыв"' )->VALUE( 'Ваш отзыв' )->get_parent_field()->location( true );
	add_field_text( 'form-submit-text' )->label( 'Текст на кнопке "Оставить отзыв"' )->VALUE( 'Оставить отзыв' )->get_parent_field()->location( true );
	
	add_field_image( 'avatar' )->label( 'Аватарка для данного отзыва' )->location()->COMMENTS();
	
	add_action( 'rest_api_init', function(){
		
		register_rest_route( 'zorbasmedia', 'add-comment', [
			'methods' => 'post',
			'callback' => function(){
				
				$post_id = \hiweb\core\Paths\PathsFactory::request( 'post_id' );
				$post = get_post( $post_id );
				if( !$post instanceof WP_Post ){
					return [ 'success' => false, 'message' => 'Не верно передан post_id' ];
				}
				$name = \hiweb\core\Paths\PathsFactory::request( 'name' );
				$text = \hiweb\core\Paths\PathsFactory::request( 'text' );
				if( strlen( $name ) < 2 || strlen( $name ) > 150 ){
					return [ 'success' => false, 'message' => 'не верно указано имя' ];
				}
				if( strlen( $text ) < 5 ){
					return [ 'success' => false, 'message' => 'не верно заполнено сообщение' ];
				}
				$recapthca = theme\recaptcha::get_recaptcha_verify();
				if( $recapthca !== true ){
					return [ 'success' => false, 'message' => get_field( 'text-error', 'hiweb-recaptcha' ), 'recaptcha_response' => $recapthca, 'post' => $_POST ];
				}
				$comment_parent = intval( \hiweb\core\Paths\PathsFactory::request( 'comment_parent' ) );
				
				$B = wp_insert_comment( [
					'comment_approved' => 0,
					'comment_author' => $name,
					'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
					'comment_content' => $text,
					'comment_post_ID' => $post_id,
					'comment_parent' => $comment_parent
				] );
				
				//Рендер блока комментариев
				$GLOBALS['post'] = get_post( $post_id );
				ob_start();
				get_template_part( 'parts/single-comments' );
				$comments_html = ob_get_clean();
				
				if( is_int( $B ) ){
					wp_mail( get_option( 'admin_email' ), 'В гостевой книге оставлен отзыв', 'Проверьте отзыв в Вашей гостевой книге.' );
					
					return [ 'success' => true, 'message' => 'Сообщение добавлено', 'comments_html' => $comments_html ];
				}
				else{
					return [ 'success' => false, 'message' => 'Ошибка добавления отзыва' ];
				}
			}
		] );
		
		register_rest_route( 'zorbasmedia', 'like-comment', [
			'methods' => 'post',
			'callback' => function(){
				
				$comment_id = \hiweb\core\Paths\PathsFactory::request( 'comment_id' );
				$comment = get_comment( $comment_id );
				if( !$comment instanceof WP_Comment ){
					return [ 'success' => false, 'message' => 'Не верно передан comment_id' ];
				}
				//проверка юзера на уникальность
				$client_id = ( new \hiweb\components\Client\Client() )->get_id_OsIp();
				$ids = (array)get_comment_meta( $comment_id, 'zorbasmedia-client-ids', true );
				if( get_array( $ids )->in( $client_id ) ){
					return [ 'success' => false, 'message' => 'Вы уже лайкали этот комментарий' ];
				}
				$ids[] = $client_id;
				update_comment_meta( $comment_id, 'zorbasmedia-client-ids', array_unique( $ids ) );
				
				$current_like = intval( get_comment_meta( $comment_id, 'zorbasmedia-likes-count', true ) );
				$current_like = ( isset( $_POST['like'] ) && intval( $_POST['like'] ) != - 1 ) ? $current_like + 1 : $current_like - 1;
				$B = update_comment_meta( $comment_id, 'zorbasmedia-likes-count', $current_like );
				
				if( $B ){
					return [ 'success' => true, 'message' => 'Комментарий обновлен', 'likes' => $current_like ];
				}
				else{
					return [ 'success' => false, 'message' => 'Ошибка обновления комментария' ];
				}
			}
		] );
	} );
	
	add_shortcode( 'hiweb-comments', function(){
		include_css( get_stylesheet_directory() . '/bootstrap-4/css/bootstrap-grid.min.css' );
		include_css( __DIR__ . '/comments.css' );
		include_js( __DIR__ . '/comments.min.js', [ 'jquery' ] );
		get_template_part( 'comments/parts/single-comments' );
		get_template_part( 'comments/parts/single-comments-form' );
	} );