<?php

	use theme\forms;
	use theme\sendpulse;


	sendpulse::$options_object = add_admin_menu_page( sendpulse::$options_name, '<i class="fas fa-cogs"></i> SendPulse', 'edit.php?post_type=' . \theme\forms::$post_type_name );
	sendpulse::$options_object->page_title( 'Интеграция с сервисом SendPulse' );

	add_field_separator( 'Подключение сайта к сервису по API. Получите ключи на странице <a href="https://login.sendpulse.com/settings/#api" target="_blank">https://login.sendpulse.com/settings/#api</a>' )->location()->ADMIN_MENUS( sendpulse::$options_name );
	add_field_text( 'api-id' )->label( 'ID' )->location()->ADMIN_MENUS( sendpulse::$options_name );
	add_field_text( 'api-secret' )->label( 'SECRET' )->location()->ADMIN_MENUS( sendpulse::$options_name );

	if( sendpulse::is_keys_exists() ){
		$options = [ 'default' => '--выберите стандартный список получателей--' ];
		if( is_admin() && sendpulse::get_instance()->is_api_exists() ){
			add_field_checkbox( 'enable-default-list-id' )->label_checkbox( 'Если для формы список не указан или не существует (его удалили), то использовать указанный список получателей рассылки ниже:' )->location( true );

			foreach( sendpulse::get_instance()->get_lists() as $id => $val ){
				$options[ 'id-' . $id ] = $val;
			}
		}
		add_field_select( 'default-list-id' )->options( $options )->label( 'Выберите стандартный список для получателей' )->location( true );
	}

	///Forms Meta Fields
	if( sendpulse::get_instance()->is_api_exists() ){
		$sendpulse_options_select = [ '' => '--выберите список для добавления адресов--' ];
		if( is_admin() ){
			$selected_id = substr( get_field( 'default-list-id', sendpulse::$options_name ), 3 );
			if( $selected_id != 'default' && array_key_exists( $selected_id, sendpulse::get_instance()->get_lists() ) ){
				$sendpulse_options_select = [ '' => 'Выбранный список в настройках: ' . sendpulse::get_instance()->get_lists()[ $selected_id ] ];
			}
			foreach( sendpulse::get_instance()->get_lists() as $id => $val ){
				$sendpulse_options_select[ 'id-' . $id ] = $val;
			}
			add_field_select( 'list-id' )->options( $sendpulse_options_select )->label( 'Выберите список адресов, в который добавлять адреса из форм' )->description( '<p>Если Вы еще не создали списки, перейдите на страницу <a href="https://login.sendpulse.com/emailservice/addressbooks/" target="_blank"><i class="fas fa-clipboard-list"></i> Создание адресной книги для рассылки</a></p> В данный список будут добавляться адреса в момент отправки формы, который был указан заполнителем.' )->location()
				->POST_TYPES( forms::$post_type_name )->META_BOX()->title( 'Установки SendPulse для данной формы' )->context()->side();
		}
		//
	} else {
		add_field_separator( '<span style="display: block; text-align: center;"><br>Ошибка подключения к SendPulse.</span><p style="text-align: center"><a href="' . get_admin_url( null, 'edit.php?post_type=' . forms::$options_name . '&page=' . sendpulse::$options_name ) . '" class="button button-primary button-large">Перейти к настройкам</a></p>' )->location()->POST_TYPES( forms::$post_type_name )->META_BOX()->title( 'Установки SendPulse для данной формы' )->context()->side();
	}