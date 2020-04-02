<?php

	namespace hiweb\components\PostType;


	use hiweb\components\FontAwesome\FontAwesomeFactory;
	use hiweb\components\PostType\PostType\PostType_Labels;
	use hiweb\components\PostType\PostType\Rewrite;
	use hiweb\components\PostType\PostType\Supports;
	use hiweb\core\Options\Options;
	use hiweb\core\Strings;
	use function hiweb\components\FontAwesome\is_fontawesome_class_name;


	class PostType extends Options{

		/** @var string */
		private $post_type_name;


		public function __construct( string $post_type_name ){
			parent::__construct();
			$this->post_type_name = Strings::sanitize_id( $post_type_name, '_', 20 );
			$this->label( $post_type_name );
			$this->menu_icon( 'fad fa-thumbtack' );
		}


		/**
		 * @return string
		 */
		public function get_post_type_name(){
			return $this->post_type_name;
		}


		/**
		 * Массив содержащий в себе названия ярлыков для типа записи.
		 * Для неустановленных строк (т.е. по умолчанию), будут использованы:
		 * Для не древовидных типов записей - названия "постов".
		 * Для древовидных типов записей - названия "постоянных страниц".
		 * @return PostType_Labels
		 */
		public function Labels(){
			if( !$this->_( 'labels' ) instanceof PostType_Labels ){
				$this->_( 'labels', new PostType_Labels( $this ) );
			}
			return $this->_( 'labels' );
		}


		/**
		 * @return Rewrite
		 */
		public function Rewrite(){
			if( !$this->_( 'rewrite' ) instanceof Rewrite ){
				$this->_( 'rewrite', new Rewrite( $this ) );
			}
			return $this->_( 'rewrite' );
		}


		/**
		 * @return Supports
		 */
		public function Supports(){
			if( !$this->_( 'supports' ) instanceof Supports ){
				$this->_( 'supports', new Supports( $this ) );
			}
			return $this->_( 'supports' );
		}


		/**
		 * Имя типа записи помеченное для перевода на другой язык.
		 * @param null|string $set
		 * @return array|PostType|mixed|null
		 */
		public function label( $set = null ){
			return $this->_( 'label', $set );
		}


		/**
		 * Короткое описание этого типа записи. Значение используется в REST API. Значение можно получить с помощью функции get_the_post_type_description().
		 * @param null|string $set
		 * @return array|PostType|mixed|null
		 */
		public function description( $set = null ){
			return $this->_( 'description', $set );
		}


		/**
		 * Определяет является ли тип записи публичным или нет. На основе этого параметра строятся много других, т.е. это своего рода пред-установка для следующих параметров:
		 * false
		 *  show_ui = false - не показывать пользовательский интерфейс (UI) для этого типа записей
		 *  publicly_queryable = false - запросы относящиеся к этому типу записей не будут работать в шаблоне
		 *  exclude_from_search = true - этот тип записей не будет учитываться при поиске по сайту
		 *  show_in_nav_menus = false - этот тип записей будет спрятан из выбора меню навигации
		 * true
		 *  show_ui = true
		 *  publicly_queryable = true
		 *  exclude_from_search = false
		 *  show_in_nav_menus = true
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function public_( $set = true ){
			return $this->_( 'public', $set );
		}


		/**
		 * true включит публичный просмотр записей этого типа - это значит что во фронт-энде будут работать URL запросы для этого типа записей, например:
		 * // без ЧПУ
		 *  ?post_type={post_type_key}
		 *  ?{post_type_key}={single_post_slug}
		 *  ?{post_type_query_var}={single_post_slug}
		 * // при включенном ЧПУ
		 *  /book/my-book-name
		 * При false записи этого типа будут недоступны во фронт-энде через обычные URL запросы и на запрос к текущему типу записи вы увидите 404 страницу.
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function publicly_queryable( $set = true ){
			return $this->_( 'publicly_queryable', $set );
		}


		/**
		 * Исключить ли этот тип записей из поиска по сайту. 1 (true) - да, 0 (false) - нет.
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function exclude_from_search( $set = true ){
			return $this->_( 'exclude_from_search', $set );
		}


		/**
		 * Определяет нужно ли создавать логику управления типом записи из админ-панели. Нужно ли создавать UI типа записи, чтобы им можно было управлять.
		 * Так, например, если указать true, а в show_in_menu = false. То у нас будет возможность зайти на страницу управления типом записи, т.е. движок будет понимать и обрабатывать такие запросы, но ссылки на эту страницу в меню не будет ...
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function show_ui( $set = true ){
			return $this->_( 'show_ui', $set );
		}


		/**
		 * Показывать ли тип записи в администраторском меню и где именно показывать управление типом записи. Аргумент show_ui должен быть включен!
		 * false - не показывать в администраторском меню.
		 * true - показывать как меню первого уровня.
		 * строка - показывать как под-меню меню первого уровня, например, подменю для 'tools.php' или 'edit.php?post_type=page' для произвольных типов меню нужно указывать $menu_slug см. add_menu_page().
		 * Если используется строка для того, чтобы показать как подменю, какого-нибудь главного меню, создаваемого плагином, этот пункт станет первым в списке и соответственно изменит расположение пунктов меню. Для того, чтобы этого не произошло, в плагине, который создает свое меню нужно установить приоритет для действия admin_menu 9 или ниже.
		 * @param null|bool|string $set
		 * @return array|PostType|mixed|null
		 */
		public function show_in_menu( $set = null ){
			return $this->_( 'show_in_menu', $set );
		}


		/**
		 * Сделать этот тип доступным из админ бара.
		 * @param bool $set
		 * @return array|PostType|mixed|null
		 */
		public function show_in_admin_bar( $set = true ){
			return $this->_( 'show_in_admin_bar', $set );
		}


		/**
		 * Включить возможность выбирать этот тип записи в меню навигации.
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function show_in_nav_menus( $set = true ){
			return $this->_( 'show_in_nav_menus', $set );
		}


		/**
		 * Нужно ли включать тип записи в REST API. true — добавит тип записи в маршрут wp/v2.
		 * Также влияет на работу блочного редактора Gutenberg: true - редактор Gutenberg включен для этого типа записи, false - будет использоваться обычный редактор.
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function show_in_rest( $set = true ){
			return $this->_( 'show_in_rest', $set );
		}


		/**
		 * Ярлык в REST API. По умолчанию, название типа записи.
		 * @param null|string $set
		 * @return array|PostType|mixed|null
		 */
		public function rest_base( $set = null ){
			return $this->_( 'rest_base', $set );
		}


		/**
		 * Название класса контроллера в REST API.
		 * По умолчанию: 'WP_REST_Posts_Controller'
		 * @param null|string $set
		 * @return array|PostType|mixed|null
		 */
		public function rest_controller_class( $set = null ){
			return $this->_( 'rest_controller_class', $set );
		}


		/**
		 * Позиция где должно расположится меню нового типа записи:
		 * 1 — в самом верху меню
		 * 2-3 — под «Консоль»
		 * 4-9 — под «Записи»
		 * 10-14 — под «Медиафайлы»
		 * 15-19 — под «Ссылки»
		 * 20-24 — под «Страницы»
		 * 25-59 — под «Комментарии» (по умолчанию, null)
		 * 60-64 — под «Внешний вид»
		 * 65-69 — под «Плагины»
		 * 70-74 — под «Пользователи»
		 * 75-79 — под «Инструменты»
		 * 80-99 — под «Параметры»
		 * 100+ — под разделителем после «Параметры»
		 * @param null $set
		 * @return array|PostType|mixed|null
		 */
		public function menu_position( $set = null ){
			return $this->_( 'menu_position', $set );
		}


		/**
		 * Ссылка на картинку, которая будет использоваться для этого меню.
		 * С выходом WordPress 3.8 появился новый пакет иконок Dashicons, который входит в состав ядра WordPress. Это комплект из более 150 векторных изображений. Чтобы установит одну из иконок, напишите её название в этот параметр. Например иконка постов, называется так: dashicons-admin-post, а ссылок dashicons-admin-links.
		 * Так же имеется поддержка FontAwesome Pro 5.12, укажите полный класс иконки "fas fa-bars"
		 * @param null $set
		 * @return array|PostType|mixed|null
		 */
		public function menu_icon( $set = null ){
			if(!is_null($set) && is_fontawesome_class_name( $set ) ){
				$icon = FontAwesomeFactory::get( $set );
				$set = 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="' . join( ',', $icon->get_style()->get_viewBox() ) . '" style="fill: none;" height="24px" width="24px">' . $icon->get_style()->get_raw() . '</svg>' );
			}
			return $this->_( 'menu_icon', $set );
		}


		/**
		 * Строка которая будет маркером для установки прав для этого типа записи.
		 * Встроенные маркеры это: post и page.
		 * Можно передавать массив, где первое значение будет использоваться для единственного числа, а второе для множественного, например: array('story', 'stories'). Если передается строка, то для множественного числа просто прибавляется 's' на конце.
		 * capability_type используется для построения списка прав, которые будут записаны в параметр 'capabilities'.
		 * При установке нестандартного маркера (не post или page), параметр map_meta_cap можно установить в true или в false:
		 * Если поставить true — то WordPress автоматически сгенерирует группу прав для параметра 'capabilities' на основе введенных здесь данных. При этом указанные в параметре 'capabilities' права дополнят имеющийся список прав.
		 * Если установить false — то WordPress ничего генерировать не будет и вам придется самому полностью прописать все возможные права для этого типа записи в параметре 'capabilities'.
		 * @param null|string|array $set
		 * @return array|PostType|mixed|null
		 */
		public function capability_type( $set = null ){
			return $this->_( 'capability_type', $set );
		}


		/**
		 * Массив прав для этого типа записи.
		 * По умолчанию, доступны 8 элементов массива, которые определяют права для этого типа записей (даже если map_meta_cap = false), это:
		 * edit_post, read_post и delete_post - 3 разрешения контролирующие редактирование, прочтение и удаление типа записи. Это мета-права: не примитивные права, которые требуют вычислений на лету. Они не записываются в список прав каждого пользователя, а превращаются в примитивные права на лету с помощью функции map_meta_cap().
		 * create_posts - алиас: тоже самое что и право edit_posts.
		 * edit_posts - контролирует возможность редактировать объекты этого типа записи.
		 * edit_others_posts - контролирует возможность редактировать объекты этого типа записей, которые принадлежат другому пользователю. Если тип записи не поддерживает авторов, то поведение этого аргумента будет похоже на edit_posts.
		 * publish_posts - контролирует публикацию объектов этого типа записи.
		 * read_private_posts - контролирует возможность прочтения личных объектов (записей).
		 * Заметка: примитивные права вида *_posts используются в движке в разных местах.
		 * Существуют еще 8 примитивных прав, которые не относятся напрямую к ядру. Но относятся к функции map_meta_cap() и проверяются там. Они устанавливаются автоматически, если указан параметр map_meta_cap = true:
		 * read - разрешает просматривать объект во фронт-энде.
		 * delete_posts - разрешает удалять объект этого типа записи.
		 * delete_private_posts - разрешает удалять личный объект этого типа записи.
		 * delete_published_posts - разрешает удалять опубликованные объекты этого типа записи.
		 * delete_others_posts - разрешает удалять записи принадлежащие другим автора. Если у записи нет автора, то поведение передается delete_posts.
		 * edit_private_posts - разрешает редактировать личные записи.
		 * edit_published_posts - разрешает редактировать опубликованные записи.
		 * create_posts - разрешает создавать новые записи.
		 * Заметка: Чтобы пользователь мог создавать новые записи, его роль должна кроме прочего иметь право 'edit_posts'.
		 * Этот capabilities параметр обычно устанавливается автоматически, опираясь на 'capability_type'.
		 * @param null $set
		 * @return array|PostType|mixed|null
		 */
		public function capabilities( $set = null ){
			return $this->_( 'capabilities', $set );
		}


		/**
		 * Ставим true, чтобы включить дефолтный обработчик специальных прав map_meta_cap(). Он преобразует неоднозначные права (edit_post - один пользователь может, а другой нет) в примитивные (edit_posts - все пользователи могут). Обычно для типов постов этот параметр нужно включать, если типу поста устанавливаются особые права (отличные от 'post').
		 * Заметка: если не установить (оставить null), то логика значения по умолчанию разветвляется:
		 * если в capability_type указано post или page и не указан параметр capabilities, то map_meta_cap = true по умолчанию.
		 * во всех остальных случаях map_meta_cap = false по умолчанию.
		 * Заметка: если установить в false, то стандартная роль "Администратор" не сможет редактировать этот тип записи. Для снятия такого ограничения придется добавить право edit_{post_type}s к роли администратор.
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function map_meta_cap( $set = null ){
			return $this->_( 'map_meta_cap', $set );
		}


		/**
		 * Будут ли записи этого типа иметь древовидную структуру (как постоянные страницы).
		 * true - да, будут древовидными
		 * false - нет, будут связаны с таксономией (категориями)
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function hierarchical( $set = null ){
			return $this->_( 'hierarchical', $set );
		}


		/**
		 * callback функция, которая будет срабатывать при установки мета блоков для страницы создания/редактирования этого типа записи. Используйте remove_meta_box() и add_meta_box() в callback функции.
		 * @param null|string|callable $set
		 * @return array|PostType|mixed|null
		 */
		public function register_meta_box_cb( $set = null ){
			return $this->_( 'register_meta_box_cb', $set );
		}


		/**
		 * Массив зарегистрированных таксономий, которые будут связаны с этим типом записей, например: category или post_tag.
		 * Связать таксономии с записью можно позднее через функцию register_taxonomy_for_object_type().
		 * Таксономии нужно регистрировать с помощью функции register_taxonomy().
		 * @param null|array $set
		 * @return array|PostType|mixed|null
		 */
		public function taxonomies( $set = null ){
			return $this->_( 'taxonomies', $set );
		}


		/**
		 * Индекс конечной точки, с которой будет ассоциирован создаваемый тип записи. Как правило этот параметр не используется. Тут можно указать след. константы или их комбинацию соединенную через & или |:
		 * EP_NONE
		 * EP_PERMALINK
		 * EP_ATTACHMENT
		 * EP_DATE
		 * EP_YEAR
		 * EP_MONTH
		 * EP_DAY
		 * EP_ROOT
		 * EP_COMMENTS
		 * EP_SEARCH
		 * EP_CATEGORIES
		 * EP_TAGS
		 * EP_AUTHORS
		 * EP_PAGES
		 * EP_ALL
		 * Конечная точка - это то что добавляется в конец URL, например /trackback/. Конечные точки прикрепляются к типу записи (добавляются в правила перезаписи) с помощью функции add_rewrite_endpoint().
		 * Этот параметр позволяет указать, какую группу конечных точек мы хотим подключить к создаваемому типу записи (к URL записи). Например, если указать 'permalink_epmask' = EP_PAGES & EP_TAGS, то наш тип записи будет иметь все дополнительные варианты URL (конечные точки), которые предусмотрены для постоянных страниц и меток.
		 * По умолчанию permalink_epmask = EP_PERMALINK - это означает, что в URL создаваемого типа записи (в правила ЧПУ) будут добавлены конечные точки, которые добавляются к обычным записям WordPress: пагинация, страница комментов и т.д.
		 * Если не нужно добавлять никаких конечных точек к новому типу записи, то нужно указать EP_NONE. Или наоборот, указываем EP_ALL, когда нужно добавить все конечные точки.
		 * @param null|string $set
		 * @return array|PostType|mixed|null
		 */
		public function permalink_epmask( $set = null ){
			return $this->_( 'permalink_epmask', $set );
		}


		/**
		 * Включить поддержку страниц архивов для этого типа записей (пр. УРЛ записи выглядит так: site.ru/type/post_name, тогда УРЛ архива будет такой: site.ru/type.
		 * Если указать строку, то она будет использована в ЧПУ. Например, укажем тут typepage и получим ссылку на архив типа записи такого вида: site.ru/typepage.
		 * Файл этого архива в теме будет иметь вид archive-type.php. Для архивов будет добавлено новое правило ЧПУ, если аргумент rewrite включен.
		 * @param null|string|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function has_archive( $set = null ){
			return $this->_( 'has_archive', $set );
		}


		/**
		 * Устанавливает название параметра запроса для создаваемого типа записи.
		 * Ставим false, чтобы убрать возможность запросов.
		 * false - отключает параметр запроса. Запись не будет доступна по URL: /?{query_var}={post_slug}.
		 * string - указывает название параметра зпроса. /?{query_var_string}={post_slug}.
		 * Заметка: query_var не имеет смысла, если параметр publicly_queryable = false.
		 * Заметка: Этот параметр добавляет указанное значение (если не указано, то ярлык типа записи) в список разрешенных параметров WordPress, чтобы WordPress понимал этот параметр запроса, см. add_rewrite_tag(). WordPress удаляет любые параметры запроса, о которых он не знает.
		 * @param null|string|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function query_var( $set = null ){
			return $this->_( 'query_var', $set );
		}


		/**
		 * Возможность экспорта этого типа записей.
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function can_export( $set = null ){
			return $this->_( 'can_export', $set );
		}


		/**
		 * true - удалять записи этого типа принадлежащие пользователю при удалении пользователя. Если включена корзина, записи не удаляться, а поместятся в корзину.
		 * false - при удалении пользователя его записи этого типа никак не будут обрабатываться.
		 * null - записи удаляться или будут перемещены в корзину, если post_type_supports('author') установлена. И не обработаются, если поддержки 'author' у типа записи нет.
		 * @param null|bool $set
		 * @return array|PostType|mixed|null
		 */
		public function delete_with_user( $set = null ){
			return $this->_( 'delete_with_user', $set );
		}


	}