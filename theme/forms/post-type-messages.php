<?php

	use theme\forms;


	$messages = add_post_type( forms::$post_type_messages_name );
	$messages->label( 'Все сообщения' )->labels()->name( 'Сообщения' )->add_new( 'Добавить сообщение' )->add_new_item( 'Создание нового сообщения' )->all_items( 'Все сообщения' )->archives( 'Архив сообщений' )->edit_item( 'Редактировать сообщение' )->items_list( 'Список сообщений' )->new_item( 'Новое сообщение' )->not_found( 'Сообщений не найдено' )->not_found_in_trash( 'Нет удаленных сообщений' )->search_items( 'Поиск сообщений' );
	$messages->show_in_menu( 'edit.php?post_type=' . forms::$post_type_name );
	$messages->public_( true )->publicly_queryable( false )->has_archive( false )->show_ui( true )->show_in_nav_menus( false )->show_in_admin_bar( false )->exclude_from_search( true );
	$messages->supports()->title();

	add_field_select( 'message-rest-exported' )->options( [ '1' => 'Экспортировать', '0' => 'Не экспортировать' ] )->label( 'Не выводить в REST для экспорта в ЦРМ' )->location()->posts( forms::$post_type_messages_name )->metaBox()->title( 'REST экспорт' )->context()->side()->priority()->high();

	add_action( 'admin_menu', function(){
		global $menu, $submenu;
		$submenu[ 'edit.php?post_type=' . forms::$post_type_name ][11][0] = '<i class="fal fa-mail-bulk"></i> Cообщения';
	} );

	add_action( 'add_meta_boxes', function( $post_type, $post ){
		if( $post_type == forms::$post_type_messages_name && $post instanceof WP_Post ){
			add_meta_box( forms::$post_type_messages_name . '-mail-data', 'Получатель и тема письма', function( $post, $meta ){
				?>
				<p>
					Получатели: <b><?= get_post_meta( $post->ID, 'form-recipient', true ) ?></b>
				</p>
				<p>
					Тема письма: <b><?= get_post_meta( $post->ID, 'form-subject', true ) ?></b>
				</p>
				<?php
			}, [ forms::$post_type_messages_name ], 'normal' );
			add_meta_box( forms::$post_type_messages_name . '-mail-content', 'Содержимое письма', function( $post, $meta ){
				echo $post->post_content;
			}, [ forms::$post_type_messages_name ], 'normal' );
			add_meta_box( forms::$post_type_messages_name . '-user-data', 'Данные клиента', function( $post, $meta ){
				?>
				<p>IP: <b><?= get_post_meta( $post->ID, 'client-ip', true ) ?></b></p>
				<p>Браузер: <b><?= get_post_meta( $post->ID, 'client-browser-name', true ) ?></b></p>
				<p>User Agent: <b><?= get_post_meta( $post->ID, 'client-user-agent', true ) ?></b></p>
				<p>ОС: <b><?= get_post_meta( $post->ID, 'client-os', true ) ?> (<?= get_post_meta( $post->ID, 'client-os2', true ) ?>)</b></p>
				<p>ID пользователя (на основании ОС, IP и браузера: <b><?= get_post_meta( $post->ID, 'client-id', true ) ?></b></p>
				<?php
			}, [ forms::$post_type_messages_name ], 'normal' );
			if( !empty( get_post_meta( $post->ID, 'utm-points', true ) ) ){
				add_meta_box( forms::$post_type_messages_name . '-utm-points', 'UTM отслеженные данные', function( $post, $meta ){
					dump_var( get_post_meta( $post->ID, 'utm-points', true ) );
				}, [ forms::$post_type_messages_name ], 'normal' );
			}
			add_meta_box( forms::$post_type_messages_name . '-form-data', 'Данные формы', function( $post, $meta ){
				dump_var( [
					'GET:' => get_post_meta( $post->ID, 'form-data-get', true ),
					'POST:' => get_post_meta( $post->ID, 'form-data-post', true )
				] );
			}, [ forms::$post_type_messages_name ], 'normal' );
		}
	}, 10, 2 );