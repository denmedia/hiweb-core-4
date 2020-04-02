<?php

	namespace hiweb\components\Taxonomies;


	use hiweb\components\FontAwesome\FontAwesomeFactory;
	use hiweb\core\Options\Options;


	class Taxonomy_Labels extends Options{

		/**
		 * Имя таксономии, обычно во множественном числе. По умолчанию _x( 'Post Tags', 'taxonomy general name' ) или _x( 'Categories', 'taxonomy general name' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function name( $set = null ){
			return $this->_( 'name', $set );
		}


		/**
		 * Название для одного элемента этой таксономии. По умолчанию _x( 'Post Tag', 'taxonomy singular name' ) или _x( 'Category', 'taxonomy singular name' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function singular_name( $set = null ){
			return $this->_( 'singular_name', $set );
		}


		/**
		 * Текст для названия меню. Эта строка обозначает название для пунктов меню. По умолчанию значение параметра name;
		 * @param null|string $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function menu_name( $set = null ){
			return $this->_( 'menu_name', $set );
		}


		/**
		 * Текст для поиска элемента таксономии. По умолчанию __( 'Search Tags' ) или __( 'Search Categories' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function search_items( $set = null ){
			return $this->_( 'search_items', $set );
		}


		/**
		 * Текст для блока популярных элементов. __( 'Popular Tags' ) или null;
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function popular_items( $set = null ){
			return $this->_( 'popular_items', $set );
		}


		/**
		 * Текст для всех элементов. __( 'All Tags' ) или __( 'All Categories' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function all_items( $set = null ){
			return $this->_( 'all_items', $set );
		}


		/**
		 * Текст для родительского элемента таксономии. Этот аргумент не используется для не древовидных таксономий. По умолчанию null или __( 'Parent Category' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function parent_item( $set = null ){
			return $this->_( 'parent_item', $set );
		}


		/**
		 * Текст для родительского элемента таксономии, тоже что и parent_item но с двоеточием в конце. По умолчанию нет или __( 'Parent Category:' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function parent_item_colon( $set = null ){
			return $this->_( 'parent_item_colon', $set );
		}


		/**
		 *Текст для редактирования элемента. По умолчанию __( 'Edit Tag' ) или __( 'Edit Category' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function edit_item( $set = null ){
			return $this->_( 'edit_item', $set );
		}


		/**
		 * Текст для обновления элемента. По умолчанию __( 'Update Tag' ) или __( 'Update Category' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function update_item( $set = null ){
			return $this->_( 'update_item', $set );
		}


		/**
		 * Текст для добавления нового элемента таксономии. По умолчанию __( 'Add New Tag' ) или __( 'Add New Category' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function add_new_item( $set = null ){
			return $this->_( 'add_new_item', $set );
		}


		/**
		 * Текст для просмотра термина таксономии. По умолчанию: "Посмотреть метку", "Посмотреть категорию". Используется например, в админ баре (тулбаре).
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function view_item( $set = null ){
			return $this->_( 'view_item', $set );
		}


		/**
		 * Текст для создания нового элемента таксономии. По умолчанию __( 'New Tag Name' ) или __( 'New Category Name' );
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function new_item_name( $set = null ){
			return $this->_( 'new_item_name', $set );
		}


		/**
		 * Текст описывающий, что элементы нужно разделять запятыми (для блога в админке). Не работает для древовидного типа. По умолчанию __( 'Separate tags with commas' ) или null;
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function separate_items_with_commas( $set = null ){
			return $this->_( 'separate_items_with_commas', $set );
		}


		/**
		 * Текст для "удаления или добавления элемента", который используется в блоке админке, при отключенном javascript. Не действует для древовидных таксономий. По умолчанию __( 'Add or remove tags' ) или null;
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function add_or_remove_items( $set = null ){
			return $this->_( 'add_or_remove_items', $set );
		}


		/**
		 * текст для блога при редактировании поста "выберите из часто используемых". Не используется для древовидных таксономий. По умолчанию __( 'Choose from the most used tags' ) или null;
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function choose_from_most_used( $set = null ){
			return $this->_( 'choose_from_most_used', $set );
		}


		/**
		 * Текст "не найдено", который отображается, если при клике на часто используемые ни один термин не был найден.
		 * @param null $set
		 * @return array|Taxonomy_Labels|mixed|null
		 */
		public function not_found( $set = null ){
			return $this->_( 'not_found', $set );
		}

	}