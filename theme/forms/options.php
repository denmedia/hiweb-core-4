<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 11:18
	 * @var \theme\forms self
	 */
	
	namespace theme\widgets;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\core\Strings;
	use theme\forms;
	
	///Options
	self::$options_object = add_admin_menu_page( self::$options_name, '<i class="fas fa-cog"></i> Опции', 'edit.php?post_type=' . self::$post_type_name );
	
	
	add_field_tab('Основные настройки','Целевой адрес получателя сообщений с форм')->location()->options(self::$options_name);
	add_field_text( 'email' )->placeholder( get_bloginfo( 'admin_email' ) )->label( 'Адрес почты, на который будет отправляться сообщения.' )->description( 'Этот адрес будет стандартным для приема сообщений. Если оставить поле пустым, письма будут отправляться на адрес супер-администратора <b>' . get_bloginfo( 'admin_email' ) . ' <a href="' . get_admin_url( null, 'options-general.php#home-description' ) . '" data-tooltip="Изменить этот адрес" title="Изменить этот адрес"><i class="fas fa-pencil-alt"></i></a></b> Для каждой формы так же можно установить индивидуальный адрес. Так же можно указать несколько адресов через запятую или пробел, например: <code>info@email.com admin@email.com</code>' )->location()->options( self::$options_name );
	add_field_text( 'reply_email' )->placeholder( 'noreply@{domain}' )->default_value( 'noreply@{domain}' )->label( 'Укажите адрес отправителя' )->description( 'Если оставить пустым адрес отправителя, он будет сформирован автоматически по шаблону noreply@{domain}<br>Допускаеться использование шорткода <code>{domain}</code> - домен текущего сайта.' )->location()->options( self::$options_name );
	
	add_field_text( 'email' )->placeholder( (string)get_field( 'email', self::$options_name ) )->label( 'Адрес почты для данной формы, на который будет отправляться сообщения.' )->description( 'Этот адрес(а) будет стандартным для приема сообщений, игнорируя общие установки адресов для всех форм. Если оставить поле пустым, письма будут отправляться на адрес, указанный в основных настройках форм <b>' . (string)get_field( 'email', self::$options_name ) . '</b> <a href="' . get_admin_url( null, 'options.php?page=' .
	                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             forms::$options_name ) . '" data-tooltip="Изменить этот адрес" title="Изменить этот адрес"><i class="fas fa-pencil-alt"></i></a></b> или на адрес супер-администратора <b>' . get_bloginfo( 'admin_email' ) . ' <a href="' . get_admin_url( null, 'options-general.php#home-description' ) . '" data-tooltip="Изменить этот адрес" title="Изменить этот адрес"><i class="fas fa-pencil-alt"></i></a></b> Так же можно указать несколько адресов через запятую или пробел, например: <code>info@email.com admin@email.com</code>' )->location()->posts( forms::$post_type_name )->position()->edit_form_after_title();
	
	add_field_text( 'reply_email' )->placeholder( (string)get_field( 'reply_email', self::$options_name ) )->label( 'Укажите адрес отправителя' )->description( 'Если оставить пустым адрес отправителя, он будет взят из опций <code>' . (string)get_field( 'reply_email', self::$options_name ) . '</code><br>Допускаеться использование шорткода <code>{domain}</code> - домен текущего сайта.' )->location()->posts( forms::$post_type_name )->position()->edit_form_after_title();
	
	add_field_tab( 'Политика конфидициальности', 'Галочка для формы с информацией' )->location(  )->options(self::$options_name);
	
	if( !get_post( (int)get_option( 'wp_page_for_privacy_policy' ) ) instanceof \WP_Post ){
		add_field_separator( 'Галочка для формы с информацией "Политика конфидициальности"', '<i class="fas fa-exclamation-triangle"></i> Настройка не возможна, так как у Вас на сайте нет страницы конфидициальности.' )->description( '' )->location()->options( self::$options_name );
	}
	else{
		add_field_separator( 'Галочка для формы с информацией "Политика конфидициальности"', 'Страница политики конфидициальности находиться по адресу <a href="' . get_permalink( (int)get_option( 'wp_page_for_privacy_policy' ) ) . '" target="_blank">' . get_the_title( (int)get_option( 'wp_page_for_privacy_policy' ) ) . '</a>' )->description( '' )->location()->options( self::$options_name );
	}
	add_field_text( 'privacy-checkbox-text' )->default_value( 'Я согласен. Отправляя данную форму, Вы соглашаетесь с {политикой конфидициальности}.' )->label( 'Текст для галочки "согласие с политикой конфидициальности"' )->description( 'Используйте шорткод <code>{политика конфидициальности}</code>для конвертации части текста в ссылку на страницу политики конфидициальности.' )->location()->options( self::$options_name );
	add_field_text( 'privacy-checkbox-error-text' )->default_value( 'Вы не согласились с Политикой конфидициальности.' )->label( 'Текст для галочки "согласие с политикой конфидициальности" в случае, если посетитель не ответил этот пункт' )->description( 'Используйте шорткод <code>{политика конфидициальности}</code>для конвертации части текста в ссылку на страницу политики конфидициальности.' )->location()->options( self::$options_name );
	add_field_checkbox( 'privacy-checkbox-default' )->label_checkbox( 'Пункт изначально всегда отмечен, посетителю перед отправкой формы не прийдеться отмечать данный пункт самостоятельно.' )->location()->options( self::$options_name );
	
	add_field_tab( 'Шаблоны писем' )->location( true );
	$strtr_descriptions = [];
	foreach(
		forms::get_strtr_templates( [
			'{data-list}' => 'Список заполненных данных',
			'{name}' => 'Содержимое данного поля (вместо {name} укажите имя поля)'
		], true ) as $key => $descript
	){
		$strtr_descriptions[] = '<code>' . $key . '</code> - ' . $descript;
	}
	$strtr_descriptions = implode( ', ', $strtr_descriptions );
	add_field_separator( 'Шаблоны писем' )->location()->options( self::$options_name );
	add_field_text( 'theme-email-admin' )->label( 'Тема письма для администратора' )->description( $strtr_descriptions )->default_value( 'На сайте {site-name} была отправлена форма' )->location()->options( self::$options_name );
	add_field_content( 'content-email-admin' )->label( 'Стандартное содердимое письма для администратора' )->description( $strtr_descriptions )->default_value( '<h3>На сайте <a href="#{home-url}">{site-name}</a> была заполнена форма "{form-title}".</h3>
Посетитель указал следующие данные:
<div style="background: #ddd; padding: .5em 1em; font-size: 1.2rem;">
{data-list}
</div>
С уважением, <a href="#{home-url}">{site-name}</a>' )->location()->options( self::$options_name );
	add_field_checkbox( 'send-client-email' )->label_checkbox( 'Отправлять письмо заполнителю формы по указанному им адресу, в случае, если в форме было поле email и оно было корректно заполнено.' )->location()->options( self::$options_name );
	add_field_text( 'theme-email-client' )->label( 'Тема письма для заполнителя' )->description( $strtr_descriptions )->default_value( 'Вы заполнили форму на сайте {site-name}' )->location()->options( self::$options_name );
	add_field_content( 'content-email-client' )->label( 'Стандартное содердимое письма для заполнителя' )->description( $strtr_descriptions )->default_value( 'Вы указали данные на сайте <a href="#{home-url}">{site-name}</a>
<div style="background: #ddd; padding: .5em 1em;">
{data-list}
</div>
С уважением, <a href="#{home-url}">{site-name}</a>' )->location()->options( self::$options_name );
	
	add_field_tab( 'Статус отправки формы AJAX', 'Иконки и сообщения о статусе отправки' )->location()->options( self::$options_name );
	add_field_fontawesome( 'icon-process' )->default_value( 'fal fa-clock' )->label( 'Иконка процесса отправки' )->location()->options( self::$options_name );
	add_field_fontawesome( 'icon-success' )->default_value( 'fal fa-comment-alt-check' )->label( 'Иконка удачной отправки сообщения' )->location()->options( self::$options_name );
	add_field_fontawesome( 'icon-warn' )->default_value( 'fal fa-comment-exclamation' )->label( 'Иконка не верно заполненной формы' )->location()->options( self::$options_name );
	add_field_fontawesome( 'icon-error' )->default_value( 'fal fa-comment-times' )->label( 'Иконка ошибки во время отправки' )->location()->options( self::$options_name );
	add_field_textarea( 'text-process' )->default_value( 'Отправка сообщения...' )->label( 'Текст отправки формы' )->location()->options( self::$options_name );
	add_field_textarea( 'text-success' )->default_value( 'Спасибо, сообщение было отправлено.' )->label( 'Текст удачной отправки формы' )->location()->options( self::$options_name );
	add_field_textarea( 'text-warn' )->default_value( 'Сообщение не отправлено, не верно заполнена форма' )->label( 'Текст ошибки заполненной формы' )->location()->options( self::$options_name );
	add_field_textarea( 'text-error' )->default_value( 'Ошибка во время отправки сообщения, попробуйте снова.' )->label( 'Текст ошибки в процессе отправки формы' )->location()->options( self::$options_name );
	//
	add_field_tab( 'UTM метки для отслежки' )->location()->options( self::$options_name );
	add_field_separator( 'UTM метки, которые необходимо отслеживать и сохранять в данных формы' )->location()->options( self::$options_name );
	add_field_textarea( 'utm-points' )->label( 'Укажите UTM метки для отслеживания и отправки в данных формы, каждую на новой строчке' )->description( 'Пример: для адреса <b>https://мойсайт.рф?<u>utm</u>=ключотметки</b> укажите на отдельной строчке <code>utm</code>' )->location()->options( self::$options_name );
	
	add_field_tab('Сбор полученных сообщений')->location(true);
	$rest_url = rest_url( '/hiweb_theme/forms/messages?key=' . urlencode( get_option( 'hiweb-option-hiweb-forms-messages-rest-key' ) ) );
	add_field_separator( 'Сбор полученных сообщений', 'Адрес для сбора письем <b><a href="' . $rest_url . '" target="_blank">' . $rest_url . '</a></b>' )->location()->options( self::$options_name );
	add_field_checkbox( 'messages-rest-enable' )->label_checkbox( 'Использовать сбор писем через REST' )->location()->options( self::$options_name );
	add_field_text( 'messages-rest-key' )->default_value( Strings::rand( 24, 1, 1, 1 ) )->label( 'Ключ, обязательный для сбора данных' )->description( 'Обязательно нажмите кнопку "сохранить", чтобы ключ зафиксировался.' )->location()->options( self::$options_name );
	add_field_checkbox( 'messages-rest-hide-showed' )->label_checkbox( 'Показывать только новые сообщения. Те, что уже были показаны, скрывать.' )->location()->options( self::$options_name );
	add_field_text( 'messages-rest-limit' )->default_value( - 1 )->label( 'Сколько выводить сообщений в REST, -1 - без ограничений' )->location()->options( self::$options_name );