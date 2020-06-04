<?php
	
	namespace hiweb\components\AdminMenu;
	
	
	use hiweb\components\FontAwesome\FontAwesome_Icon;
	use hiweb\components\FontAwesome\FontAwesome_Icon_Style;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	use hiweb\core\Options\Options;
	use hiweb\core\Strings;
	use function hiweb\components\FontAwesome\is_fontawesome_class_name;
	
	
	class AdminMenu_Page extends Options{
		
		
		protected $menu_slug;
		/** @var FontAwesome_Icon */
		protected $fontawesome_icon;
		
		
		public function __construct( $slug ){
			parent::__construct();
			$this->menu_slug = Strings::sanitize_id( $slug, '_', 20 );
		}
		
		
		/**
		 * Текст, который будет использован в теге <title> на странице, относящейся к пункту меню.
		 * @param null $set
		 * @return array|AdminMenu_Page|mixed|null
		 */
		public function page_title( $set = null ){
			return $this->_( 'page_title', $set );
		}
		
		
		/**
		 * Название пункта меню в сайдбаре админ-панели.
		 * @param string $set
		 * @return array|AdminMenu_Page|mixed|null
		 */
		public function menu_title( $set = null ){
			if( is_null( $set ) && $this->parent_slug() != '' && $this->fontawesome_icon instanceof FontAwesome_Icon ){
				return '<span style="display: inline-block; width: 1em; margin-right: .4em; vertical-align: middle">' . $this->fontawesome_icon->get_style()->get_raw() . '</span>' . $this->_( 'menu_title' );
			}
			else{
				return $this->_( 'menu_title', $set );
			}
		}
		
		
		/**
		 * Права пользователя (возможности), необходимые чтобы пункт меню появился в списке.
		 * @param string $set
		 * @return array|AdminMenu_Page|mixed|null
		 */
		public function capability( $set = null ){
			return $this->_( 'capability', $set );
		}
		
		
		/**
		 * Уникальное название (slug), по которому затем можно обращаться к этому меню.
		 * Если параметр $function не указан, этот параметр должен равняться названию PHP файла относительно каталога плагинов, который отвечает за вывод кода страницы этого пункта меню.
		 * Можно указать произвольную ссылку (URL), куда будет вести клик пункта меню.
		 * @return string
		 */
		public function menu_slug(){
			return $this->menu_slug;
		}
		
		
		/**
		 * Название (slug) родительского меню в которое будет добавлен пункт или название файла админ-страницы WordPress.
		 * Используйте NULL, чтобы создать страницу, которая не будет появляться в пункте меню. Работает и для мультисайта.
		 * Примеры:
		 * index.php - Консоль (Dashboard). Или спец. функция: add_dashboard_page();
		 * edit.php - Посты (Posts). Или спец. функция: add_posts_page();
		 * upload.php - Медиафайлы (Media). Или спец. функция: add_media_page();
		 * link-manager.php - Ссылки (Links). Или спец. функция: add_links_page();
		 * edit.php?post_type=page - Страницы (Pages). Или спец. функция: add_pages_page();
		 * edit-comments.php - Комментарии (Comments). Или спец. функция: add_comments_page();
		 * edit.php?post_type=your_post_type - Произвольные типы записей.
		 * themes.php - Внешний вид (Appearance). Или спец. функция: add_theme_page();
		 * plugins.php - Плагины (Plugins). Или спец. функция: add_plugins_page();
		 * users.php - Пользователи (Users). Или спец. функция: add_users_page();
		 * tools.php - Инструменты (Tools). Или спец. функция: add_management_page();
		 * options-general.php - Настройки (Settings). Или спец. функция: add_options_page()
		 * settings.php - Настройки (Settings) сети сайтов в MU режиме.
		 * @param null|string $set
		 * @return array|AdminMenu_Page|mixed|null
		 */
		public function parent_slug( $set = null ){
			return $this->_( 'parent_slug', $set );
		}
		
		
		/**
		 * Название функции, которая выводит контент страницы пункта меню.
		 * Этот необязательный параметр и если он не указан, WordPress ожидает что текущий подключаемый PHP файл генерирует код страницы админ-меню, без вызова функции. Большинство авторов плагинов предпочитают указывать этот параметр.
		 * Два варианта установки параметра:
		 * Если функция является методом класса, она вызывается по ссылке:
		 * array( $this, 'function_name' )
		 * или статически:
		 * array( __CLASS__, 'function_name' ).
		 * Во всех остальных случаях указываем название функции в виде строки.
		 * @param null $callback
		 * @return array|AdminMenu_Page|mixed|null
		 */
		public function function_( $callback = null ){
			return $this->_( 'function', $callback );
		}
		
		
		/**
		 * Иконка для пункта меню.
		 * @param null $urlOrFontawesome
		 * @return array|AdminMenu_Page|mixed|null
		 */
		public function icon_url( $urlOrFontawesome = null ){
			if( !is_null( $urlOrFontawesome ) && is_fontawesome_class_name( $urlOrFontawesome ) ){
				$this->fontawesome_icon = FontAwesomeFactory::get( $urlOrFontawesome );
				$urlOrFontawesome = 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="' . join( ',', $this->fontawesome_icon->get_style()->get_viewBox() ) . '" style="fill: none;" height="24px" width="24px">' . $this->fontawesome_icon->get_style()->get_raw() . '</svg>' );
			}
			return $this->_( 'icon_url', $urlOrFontawesome );
		}
		
		
		/**
		 * Число определяющее позицию меню. Чем больше цифра, тем ниже будет расположен пункт меню.
		 * Внимание! Если два пункта используют одинаковую цифру-позицию, один из пунктов меню может быть перезаписан и будет показан только один пункт из двух. Чтобы избежать конфликта, можно использовать десятичные значения, вместо целых чисел: 63.3 вместо 63. Используйте кавычки: "63.3".
		 * По умолчанию, пункт меню будет добавлен в конец списка.
		 * Список позиций для базовых пунктов меню:
		 * 2 Консоль
		 * 4 Разделитель
		 * 5 Посты
		 * 10 Медиа
		 * 15 Ссылки
		 * 20 Страницы
		 * 25 Комментарии
		 * 59 Разделитель
		 * 60 Внешний вид
		 * 65 Плагины
		 * 70 Пользователи
		 * 75 Инструменты
		 * 80 Настройки
		 * 99 Разделитель
		 * @param null $set
		 * @return array|AdminMenu_Page|mixed|null
		 */
		public function position( $set = null ){
			return $this->_( 'position', $set );
		}
		
		
		public function the_page(){
			if( isset( $_GET['settings-updated'] ) ){
				if( $_GET['settings-updated'] ){
					if( get_current_screen()->parent_file != 'options-general.php' ){
//						$notice = add_admin_notice( 'Для страницы "' . $this->page_title() . '" все данные успешно сохранены' );
//						$notice->CLASS_()->success();
//						$notice->the();
					}
				} else {
//					$notice = add_admin_notice( 'Ошибка в момент сохранения опций' );
//					$notice->CLASS_()->error();
//					$notice->the();
				}
			}
			include __DIR__ . '/template-default.php';
		}
		
		
		public function submit_button_label( $set = null ){
			return $this->_( 'submit_button_label', $set, __( 'Update' ) );
		}
		
		public function submit_button_icon($fontAwesome_icon = null){
			return $this->_('submit_button_icon', $fontAwesome_icon);
		}
		
	}