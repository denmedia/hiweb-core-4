<?php

	namespace hiweb\components\PostType\PostType;


	use hiweb\core\Options\Options;


	class PostType_Labels extends Options{

		/**
		 * основное название для типа записи, обычно во множественном числе.
		 * @param null/string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function name( $set = null ){
			return $this->_( 'name', $set );
		}


		/**
		 * название для одной записи этого типа.
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function singular_name( $set = null ){
			return $this->_( 'singular_name', $set );
		}


		/**
		 * текст для добавления новой записи, как "добавить новый" у постов в админ-панели.
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function add_new( $set = null ){
			return $this->_( 'add_new', $set );
		}


		/**
		 * текст заголовка у вновь создаваемой записи в админ-панели. Как "Добавить новый пост" у постов.
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function add_new_item( $set = null ){
			return $this->_( 'add_new_item', $set );
		}


		/**
		 * текст для редактирования типа записи. По умолчанию: редактировать пост/редактировать страницу.
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function edit_item( $set = null ){
			return $this->_( 'edit_item', $set );
		}


		/**
		 * текст новой записи. По умолчанию: "Новый пост"
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function new_item( $set = null ){
			return $this->_( 'new_item', $set );
		}


		/**
		 * текст для просмотра записи этого типа. По умолчанию: "Посмотреть пост"/"Посмотреть страницу".
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function view_item( $set = null ){
			return $this->_( 'view_item', $set );
		}


		/**
		 * текст для поиска по этим типам записи. По умолчанию "Найти пост"/"найти страницу".
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function search_items( $set = null ){
			return $this->_( 'search_items', $set );
		}


		/**
		 * текст, если в результате поиска ничего не было найдено.
		 * По умолчанию: "Постов не было найдено"/"Страниц не было найдено".
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function not_found( $set = null ){
			return $this->_( 'not_found', $set );
		}


		/**
		 * текст, если не было найдено в корзине. По умолчанию "Постов не было найдено в корзине"/"Страниц не было найдено в корзине".
		 * По умолчанию: "Постов не было найдено"/"Страниц не было найдено".
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function not_found_in_trash( $set = null ){
			return $this->_( 'not_found_in_trash', $set );
		}


		/**
		 * текст для родительских типов. Этот параметр не используется для не древовидных типов записей.
		 * По умолчанию "Родительская страница".
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function parent_item_colon( $set = null ){
			return $this->_( 'parent_item_colon', $set );
		}


		/**
		 * Все записи. По умолчанию равен menu_name
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function all_items( $set = null ){
			return $this->_( 'all_items', $set );
		}


		/**
		 * Архивы записей. По умолчанию равен all_items
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function archives( $set = null ){
			return $this->_( 'archives', $set );
		}


		/**
		 * Вставить в запись
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function insert_into_item( $set = null ){
			return $this->_( 'insert_into_item', $set );
		}


		/**
		 * Загружено для этой записи
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function uploaded_to_this_item( $set = null ){
			return $this->_( 'uploaded_to_this_item', $set );
		}


		/**
		 * Миниатюра записи
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function featured_image( $set = null ){
			return $this->_( 'featured_image', $set );
		}


		/**
		 * Установить миниатюру записи
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function set_featured_image( $set = null ){
			return $this->_( 'set_featured_image', $set );
		}


		/**
		 * Удалить миниатюру записи
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function remove_featured_image( $set = null ){
			return $this->_( 'remove_featured_image', $set );
		}


		/**
		 * Использовать как миниатюру записи
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function use_featured_image( $set = null ){
			return $this->_( 'use_featured_image', $set );
		}


		/**
		 * Фильтровать список записей
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function filter_items_list( $set = null ){
			return $this->_( 'filter_items_list', $set );
		}


		/**
		 * Навигация по записям
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function items_list_navigation( $set = null ){
			return $this->_( 'items_list_navigation', $set );
		}


		/**
		 * Список записей
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function items_list( $set = null ){
			return $this->_( 'items_list', $set );
		}


		/**
		 * Название меню. По умолчанию равен name.
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function menu_name( $set = null ){
			return $this->_( 'menu_name', $set );
		}


		/**
		 * Название в админ баре (тулбаре). По умолчанию равен singular_name.
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function name_admin_bar( $set = null ){
			return $this->_( 'name_admin_bar', $set );
		}


		/**
		 * Название в админ баре (тулбаре). По умолчанию равен singular_name.
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function view_items( $set = null ){
			return $this->_( 'view_items', $set );
		}


		/**
		 * Название для метабокса атрибутов записи (у страниц это метабокс «Свойства страницы» - «Page Attributes»).
		 * По умолчанию: «Post Attributes» или «Page Attributes». С WP 4.7.
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function attributes( $set = null ){
			return $this->_( 'attributes', $set );
		}


		/**
		 * Текст заметки в редакторе записи при обновлении записи. С WP 5.0.
		 * По умолчанию: «Post updated.» / «Page updated.»
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function item_updated( $set = null ){
			return $this->_( 'item_updated', $set );
		}


		/**
		 * Текст заметки в редакторе записи при публикации записи. С WP 5.0.
		 * По умолчанию: «Post published.» / «Page published.»
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function item_published( $set = null ){
			return $this->_( 'item_published', $set );
		}


		/**
		 * Текст заметки в редакторе записи при публикации private записи. С WP 5.0.
		 * По умолчанию: «Post published privately.» / «Page published
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function item_published_privately( $set = null ){
			return $this->_( 'item_published_privately', $set );
		}


		/**
		 * Текст заметки в редакторе записи при возврате записи в draft. С WP 5.0.
		 * По умолчанию: «Post reverted to draft.» / «Page reverted to
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function item_reverted_to_draft( $set = null ){
			return $this->_( 'item_reverted_to_draft', $set );
		}


		/**
		 * Текст заметки в редакторе записи при запланированной публикации на будущую дату. С WP 5.0.
		 * По умолчанию: «Post scheduled.» / «Page scheduled.»
		 * @param null|string $set
		 * @return array|PostType_Labels|mixed|null
		 */
		public function item_scheduled( $set = null ){
			return $this->_( 'item_scheduled', $set );
		}

	}