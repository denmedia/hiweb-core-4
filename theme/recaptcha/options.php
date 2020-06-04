<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 18:12
	 */

	use theme\recaptcha;
	use theme\includes\admin;


	admin::fontawesome(false);
	recaptcha::$options_object_recaptcha = add_admin_menu_page( recaptcha::$admin_menu_slug, '<i class="fas fa-user-check"></i> reCaptcha', recaptcha::$admin_menu_parent );
	add_field_separator( 'Google reCaptcha v.3', 'Воспользуйтесь Google reCaptcha (анти-бот проверка) для фильтрации ботов.<br><a href="https://www.google.com/recaptcha/admin#v3signup" target="_blank"><i class="fas fa-key"></i> Получите секретный и публичный ключ на странице сервиса.</a><br><a href="https://developers.google.com/recaptcha/docs/v3" target="_blank"><i class="fas fa-book"></i> Документация сервиса.</a>' )->location()->options( recaptcha::$admin_menu_slug );
	add_field_checkbox( 'enable' )->label_checkbox( 'Включить проверку reCaptcha' )->location( true );
	add_field_text( 'public-key' )->label( 'Публичный ключ' )->location( true );
	add_field_text( 'private-key' )->label( 'Секретный (приватный) ключ' )->location( true );
	add_field_text( 'text-error' )->label( 'Сообщение о неудачной проверка анти-бот фильтра' )->default_value( 'Ошибка проверки анти-бот фильтра' )->location( true );
	add_field_text('min-score')->default_value('0.1')->label('Минимальный бал допуска. от 0 до 1.')->description('Данный бал, определяемый сервисом Google reCaptcha v.3, означает вероятность "человечности".<br>Например, <b>0 баллов</b> - означает максимальную вероятность бота, иногда спам может проходить от <b>0.1 балла</b>. Если спам имеет место быть, необходимо поднять бал на 0.1 или выше, чтобы снизить вероятность прохода анти-бот фильтра ботами.')->location(true);