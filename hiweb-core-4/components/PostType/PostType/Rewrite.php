<?php

	namespace hiweb\components\PostType\PostType;


	use hiweb\core\Options\Options;


	class Rewrite extends Options{

		/**
		 * Префикс в ЧПУ (/префикс/ярлык_записи). Используйте array( 'slug' => $slug ), чтобы создать другой префикс.
		 * В этом параметре можно указывать плейсхолдеры типа %category%. Но их нужно создать с помощью add_rewrite_tag() и научить WP их понимать.
		 * @param null|string $set
		 * @return array|Rewrite|mixed|null
		 */
		public function slug( $set = null ){
			return $this->_( 'slug', $set );
		}


		/**
		 * Нужно ли в начало вставлять общий префикс из настроек. Префикс берется из $wp_rewite->front. Например, если структура постоянных ссылок записей в настройках имеет вид blog/%postname%, то при false получим: /news/название_поста, а при true получим: /blog/news/название_поста.
		 * @param null $set
		 * @return array|Rewrite|mixed|null
		 */
		public function with_front( $set = null ){
			return $this->_( 'with_front', $set );
		}


		/**
		 * Добавить ли правило ЧПУ для RSS ленты этого типа записи.
		 * @param null $set
		 * @return array|Rewrite|mixed|null
		 */
		public function feeds( $set = null ){
			return $this->_( 'feeds', $set );
		}


		/**
		 * Добавить ли правило ЧПУ для пагинации архива записей этого типа. Пр: /post_type/page/2.
		 * @param null $set
		 * @return array|Rewrite|mixed|null
		 */
		public function pages( $set = null ){
			return $this->_( 'pages', $set );
		}

	}