<?php

	namespace hiweb\components\Taxonomies;


	use hiweb\core\Options\Options;
	use hiweb\core\Strings;


	class Taxonomy extends Options{

		/** @var Taxonomy_Labels */
		private $labels;


		public function __construct( string $taxonomy_name ){
			parent::__construct();
			$taxonomy_name = Strings::sanitize_id( $taxonomy_name, '_', 32 );
			$this->_( 'taxonomy', $taxonomy_name );
		}


		/**
		 * Return taxonomy name
		 * @return array|mixed|null
		 */
		public function taxonomy(){
			return $this->get( 'taxonomy' );
		}


		/**
		 * Название типов постов, к которым будет привязана таксономия. В этом параметре, например, можно указать 'post', тогда у обычных постов WordPress появится новая таксономия (возможность классификации).
		 * @param null|string|array $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function object_type( $set = null ){
			return $this->_( 'object_type', $set );
		}


		/**
		 * Название таксономии во множественном числе (для отображения в админке).
		 * По умолчанию: используется значение аргумента $labels->name
		 * @param null $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function label( $set = null ){
			return $this->_( 'label', $set );
		}


		/**
		 * Массив описывающий заголовки таксономии (для отображения в админке).
		 * По умолчанию используются заголовки "меток" для не древовидных типов таксономий и заголовки "категорий" для древовидных таксономий.
		 * @return Taxonomy_Labels
		 */
		public function labels(){
			if( !$this->_( 'labels' ) instanceof Taxonomy_Labels ){
				$this->_( 'labels', new Taxonomy_Labels( $this ) );
			}
			return $this->_( 'labels' );
		}


		/**
		 * Показывать ли эту таксономию в интерфейсе админ-панели. Это значение передается параметрам publicly_queryable, show_ui, show_in_nav_menus если для них не установлено свое значение.
		 * По умолчанию: true
		 * @param null $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function public_( $set = null ){
			return $this->_( 'public', $set );
		}


		/**
		 * Показывать блок управления этой таксономией в админке.
		 * По умолчанию: если нет, равно аргументу public
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function show_ui( $set = null ){
			return $this->_( 'show_ui', $set );
		}


		/**
		 * Показывать ли таксономию в админ-меню.
		 * true - таксономия будет показана как подменю у типа записи, к которой она прикреплена.
		 * false - подменю не будет показано.
		 * Параметр $show_ui должен быть включен (true).
		 * По умолчанию: если нет, равно аргументу show_ui
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function show_in_menu( $set = null ){
			return $this->_( 'show_in_menu', $set );
		}


		/**
		 * true даст возможность выбирать элементы этой таксономии в навигационном меню.
		 * По умолчанию: если нет, равно аргументу public
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function show_in_nav_menus( $set = null ){
			return $this->_( 'show_in_nav_menus', $set );
		}


		/**
		 * Создать виджет облако элементов этой таксономии (как облако меток).
		 * По умолчанию: если нет, равно аргументу show_ui
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function show_tagcloud( $set = null ){
			return $this->_( 'show_tagcloud', $set );
		}


		/**
		 * Нужно ли включать таксономию в REST API.
		 * Также влияет на работу блочного редактора Gutenberg: true - таксономия будет видна в редакторе блоков Gutenberg, false - такса будет видна только в обычном редакторе.
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function show_in_rest( $set = null ){
			return $this->_( 'show_in_rest', $set );
		}


		/**
		 * Ярлык в REST API. По умолчанию, название таксономии.
		 * По умолчанию: $taxonomy
		 * @param null|string $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function rest_base( $set = null ){
			return $this->_( 'rest_base', $set );
		}


		/**
		 * Название класса контроллера в REST API.
		 * По умолчанию: 'WP_REST_Terms_Controller'
		 * @param null|string $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function rest_controller_class( $set = null ){
			return $this->_( 'rest_controller_class', $set );
		}


		/**
		 * true - таксономия будет древовидная (как категории). false - будет не древовидная (как метки).
		 * По умолчанию: false
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function hierarchical( $set = null ){
			return $this->_( 'hierarchical', $set );
		}


		/**
		 * Название функции, которая будет вызываться для обновления количества записей в данной таксономии (у термина), для типа записи ассоциированного с этой таксономией.
		 * По умолчанию:
		 * _update_post_term_count для таксономий прикрепленных к типам записей.
		 * _update_generic_term_count для таксономий, присоединенных к другим объектам, например, к юзерам.
		 * Функция получит следующие параметры:
		 * $terms — term_taxonomy_id терминов которые нужно обновить.
		 * $taxonomy — Объект таксономии.
		 * @param null|string|callable $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function update_count_callback( $set = null ){
			return $this->_( 'update_count_callback', $set );
		}


		/**
		 * false - отключит перезапись. Если указать массив, то можно задать произвольный параметр запроса (query var). А по умолчанию будет использоваться параметр $taxonomy.
		 * Возможные аргументы массива:
		 * slug - предваряет таксономии этой строкой. По умолчанию название таксономии;
		 * with_front - позволяет установить префикс для постоянной ссылки. По умолчанию true;
		 * hierarchical - true - включает поддержку древовидных URL (с версии 3.1). По умолчанию false;
		 * Массив передается в функцию add_permastruct(), поэтому тут также можно указать аргументы этой функции.
		 * По умолчанию: true
		 * @param null|bool|array $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function rewrite( $set = null ){
			return $this->_( 'rewrite', $set );
		}


		/**
		 * Имеют ли пользователи доступ к элементам таксономии во внешней части сайта. Если не установлено, то берется значение параметра public. C версии 4.5.
		 * По умолчанию: null (равен аргументу public)
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function publicly_queryable( $set = null ){
			return $this->_( 'publicly_queryable', $set );
		}


		/**
		 * Если указать false, выключит параметры запроса и сам запрос. Или можно указать строку, чтобы изменить параметр запроса (query var). По умолчанию будет использоваться параметр $taxonomy - название таксономии.
		 * По умолчанию: $taxonomy
		 * @param null|string|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function query_var( $set = null ){
			return $this->_( 'query_var', $set );
		}


		/**
		 * Массив прав для этой таксономии:
		 * manage_terms - 'manage_categories'
		 * edit_terms - 'manage_categories'
		 * delete_terms - 'manage_categories'
		 * assign_terms - 'edit_posts'
		 * @param null|array $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function capabilities( $set = null ){
			return $this->_( 'capabilities', $set );
		}


		/**
		 * callback функция. Отвечает за то, как будет отображаться таксономия в метабоксе (с версии 3.8).
		 * Встроенные названия функций:
		 * post_categories_meta_box - показывать как категории
		 * post_tags_meta_box - показывать как метки.
		 * Если указать false, то метабокс будет отключен вообще.
		 * По умолчанию: null
		 * @param null|string $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function meta_box_cb( $set = null ){
			return $this->_( 'meta_box_cb', $set );
		}


		/**
		 * Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
		 * По умолчанию: false
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function show_admin_column( $set = null ){
			return $this->_( 'show_admin_column', $set );
		}


		/**
		 * Показывать ли таксономию в панели быстрого редактирования записи (в таблице, списке всех записей, при нажатии на кнопку "свойства"). С версии 4.2.
		 * По умолчанию: null (значение show_ui)
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function show_in_quick_edit( $set = null ){
			return $this->_( 'show_in_quick_edit', $set );
		}


		/**
		 * Следует ли этой таксономии запоминать порядок в котором созданные элементы (термины) прикрепляются к объектам (записям).
		 * Например, для тегов, если этот параметр true, то при получении тегов они должны выводиться в том порядке, в котором они были указаны (добавлены) для записи. Т.е. если этот флаг установлен, то сортировка терминов должна быть не по name а по полю term_order.
		 * При true в таблицу wp_term_relationships в поле term_order будет записываться число - порядок в котором расположены рубрики, в которые добавлена запись. Чаще всего эта настройка не нужна, более того, параметр этот есть, но в коде он нигде не прописан и по факту ни на что не виляет.
		 * По умолчанию: null
		 * @param null|bool $set
		 * @return array|Taxonomy|mixed|null
		 */
		public function sort( $set = null ){
			return $this->_( 'sort', $set );
		}


	}