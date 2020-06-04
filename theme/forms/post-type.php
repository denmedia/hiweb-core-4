<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 18:18
	 */

	use theme\forms;
	use theme\forms\inputs\button;
	use theme\forms\inputs\checkbox;
	use theme\forms\inputs\checkboxes;
	use theme\forms\inputs\email;
	use theme\forms\inputs\html_insert;
	use theme\forms\inputs\image;
	use theme\forms\inputs\info_text;
	use theme\forms\inputs\json;
	use theme\forms\inputs\listing;
	use theme\forms\inputs\number;
	use theme\forms\inputs\phone;
	use theme\forms\inputs\postlink;
	use theme\forms\inputs\privacy;
	use theme\forms\inputs\text;
	use theme\forms\inputs\textarea;


	self::$post_type_object = add_post_type( self::$post_type_name );
	self::$post_type_object->menu_icon( 'fas fa-comment-alt-edit' );
	//self::$post_type->menu_icon('data:image/svg+xml;base64,');
	self::$post_type_object->labels()->menu_name( 'Формы на сайте' )->name( 'Формы' );
	self::$post_type_object->supports()->title();
	self::$post_type_object->public_( true )->publicly_queryable( false )->has_archive( false )->show_ui( true )->show_in_menu( true )->show_in_nav_menus( false )->show_in_admin_bar( false )->exclude_from_search( true );
	///
	add_action( 'admin_menu', function(){
		global $menu, $submenu;
		$submenu[ 'edit.php?post_type=' . forms::$post_type_name ][5][0] = '<i class="fal fa-list-alt"></i> Формы';
		$submenu[ 'edit.php?post_type=' . forms::$post_type_name ][10][0] = '<i class="fal fa-comment-alt-plus"></i> Создать форму';
	} );
	///
	add_field_tab('Форма')->location()->posts( self::$post_type_name );
	$INPUTS = add_field_repeat( 'inputs' );
	$INPUTS->label( 'Поля ввода' )->location()->posts( self::$post_type_name )->COLUMNS_MANAGER()->name( 'Шорткоды' )->callback( function( $post_id ){
		echo '<p>просто форма:<br><code>[hiweb-theme-widget-form id="' . $post_id . '"]</code></p><p>кнопка, вызывающая форму:<br><code>[hiweb-theme-widget-form-button id="' . $post_id . '" html="Открыть форму"]</code></p>';
	} );
	//
	text::add_repeat_field( $INPUTS );
	number::add_repeat_field( $INPUTS );
	textarea::add_repeat_field( $INPUTS );
	email::add_repeat_field( $INPUTS );
	phone::add_repeat_field( $INPUTS );
	checkbox::add_repeat_field( $INPUTS );
	checkboxes::add_repeat_field( $INPUTS );
	privacy::add_repeat_field( $INPUTS );
	button::add_repeat_field( $INPUTS );
	json::add_repeat_field( $INPUTS );
	postlink::add_repeat_field( $INPUTS );
	info_text::add_repeat_field( $INPUTS );
	html_insert::add_repeat_field( $INPUTS );
	image::add_repeat_field( $INPUTS );
	listing::add_repeat_field( $INPUTS );
	//

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
	//
	add_field_tab('Статус отправки данной формы')->location()->posts( self::$post_type_name );
	add_field_separator( 'Статус отправки формы AJAX', 'Эти настройки актуальны только для данной формы. Если оставить их незаполненными, вместо них будут использованы стандартные установки со страницы <a data-tooltip="Открыть страницу опций" href="' . get_admin_url( null, 'edit.php?post_type=' . self::$post_type_name . '&page=' . self::$options_name ) . '">Опции формы</a>' )->location()->posts( self::$post_type_name );
	add_field_fontawesome( 'icon-process' )->label( 'Иконка процесса отправки' )->location( true );
	add_field_fontawesome( 'icon-success' )->label( 'Иконка удачной отправки сообщения' )->location( true );
	add_field_fontawesome( 'icon-warn' )->label( 'Иконка не верно заполненной формы' )->location( true );
	add_field_fontawesome( 'icon-error' )->label( 'Иконка ошибки во время отправки' )->location( true );
	add_field_textarea( 'text-process' )->label( 'Текст отправки формы' )->location( true );
	add_field_textarea( 'text-success' )->label( 'Текст удачной отправки формы' )->location( true );
	add_field_textarea( 'text-warn' )->label( 'Текст ошибки заполненной формы' )->location( true );
	add_field_textarea( 'text-error' )->label( 'Текст ошибки в процессе отправки формы' )->location( true );
	add_field_tab('Шаблоны писем')->location(true);
	add_field_separator( 'Шаблоны писем для данной формы', 'Эти настройки шаблонов актуальны только для данной формы. Если оставить их незаполненными, вместо них будут использованы стандартные установки со страницы <a data-tooltip="Открыть страницу опций" href="' . get_admin_url( null, 'edit.php?post_type=' . self::$post_type_name . '&page=' . self::$options_name ) . '">Опции формы</a>' )->location()->posts( self::$post_type_name );
	add_field_text( 'theme-email-admin' )->label( 'Тема письма для администратора' )->description( $strtr_descriptions )->location( true );
	add_field_content( 'content-email-admin' )->label( 'Стандартное содердимое письма для администратора' )->description( $strtr_descriptions )->location( true );
	add_field_checkbox( 'send-client-email' )->label_checkbox( 'Отправлять письмо заполнителю формы по указанному им адресу, в случае, если в форме было поле email и оно было корректно заполнено.' )->location( true );
	add_field_text( 'theme-email-client' )->label( 'Тема письма для заполнителя' )->description( $strtr_descriptions )->location( true );
	add_field_content( 'content-email-client' )->label( 'Стандартное содердимое письма для заполнителя' )->description( $strtr_descriptions )->location( true );
	///
	add_field_tab('JavaScript Events')->location(true);
	add_field_textarea( 'callback_js' )->label( 'JavaScript, который будет выполнен в случае удачной отправки формы.' )->description( "Пример заполнения: <code>let foo = 'bar';\nalert(foo);</code>" )->location( true );