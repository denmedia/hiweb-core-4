<?php

	namespace hiweb\components\PostType\PostType;


	use hiweb\core\Options\Options;


	class Supports extends Options{

		/**
		 * блок заголовка;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function title( $set = true ){
			return $set ? $this->_( 'title', true ) : $this->remove( 'title' );
		}


		/**
		 * блок для ввода контента;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function editor( $set = true ){
			return $set ? $this->_( 'editor', true ) : $this->remove( 'editor' );
		}


		/**
		 * блок выбора автора;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function author( $set = true ){
			return $set ? $this->_( 'author', true ) : $this->remove( 'author' );
		}


		/**
		 * блок выбора миниатюры записи. Нужно также включить поддержку в установке темы post-thumbnails;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function thumbnail( $set = true ){
			add_theme_support( 'post-thumbnails' );
			return $set ? $this->_( 'thumbnail', true ) : $this->remove( 'thumbnail' );
		}


		/**
		 * блок ввода цитаты;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function excerpt( $set = true ){
			return $set ? $this->_( 'excerpt', true ) : $this->remove( 'excerpt' );
		}


		/**
		 * включает поддержку трекбеков и пингов (за блоки не отвечает);
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function trackbacks( $set = true ){
			return $set ? $this->_( 'trackbacks', true ) : $this->remove( 'trackbacks' );
		}


		/**
		 * блок установки произвольных полей;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function custom_fields( $set = true ){
			return $set ? $this->_( 'custom-fields', true ) : $this->remove( 'custom-fields' );
		}


		/**
		 * блок комментариев (обсуждение);
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function comments( $set = true ){
			return $set ? $this->_( 'comments', true ) : $this->remove( 'comments' );
		}


		/**
		 * блок атрибутов постоянных страниц (шаблон и древовидная связь записей, древовидность должна быть включена).
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function page_attributes( $set = true ){
			return $set ? $this->_( 'page-attributes', true ) : $this->remove( 'page-attributes' );
		}


		/**
		 * блок форматов записи, если они включены в теме.
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function post_formats( $set = true ){
			return $set ? $this->_( 'post-formats', true ) : $this->remove( 'post-formats' );
		}

		protected function get( $option_key = null, $default = null ){
			return $this->options_ArrayObject()->get_keys();
		}

	}