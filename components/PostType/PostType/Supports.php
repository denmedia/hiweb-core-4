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
			if( $set ){
				$this->options_ArrayObject()->push( __FUNCTION__ );
			}
			else{
				$this->options_ArrayObject()->unset_value( __FUNCTION__ );
			}
			return $this;
		}
		
		
		/**
		 * блок для ввода контента;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function editor( $set = true ){
			if( $set ){
				$this->options_ArrayObject()->push( __FUNCTION__ );
			}
			else{
				$this->options_ArrayObject()->unset_value( __FUNCTION__ );
			}
			return $this;
		}
		
		
		/**
		 * блок выбора автора;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function author( $set = true ){
			if( $set ){
				$this->options_ArrayObject()->push( __FUNCTION__ );
			}
			else{
				$this->options_ArrayObject()->unset_value( __FUNCTION__ );
			}
			return $this;
		}
		
		
		/**
		 * блок выбора миниатюры записи. Нужно также включить поддержку в установке темы post-thumbnails;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function thumbnail( $set = true ){
			add_theme_support( 'post-thumbnails' );
			if( $set ){
				$this->options_ArrayObject()->push( __FUNCTION__ );
			}
			else{
				$this->options_ArrayObject()->unset_value( __FUNCTION__ );
			}
			return $this;
		}
		
		
		/**
		 * блок ввода цитаты;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function excerpt( $set = true ){
			if( $set ){
				$this->options_ArrayObject()->push( __FUNCTION__ );
			}
			else{
				$this->options_ArrayObject()->unset_value( __FUNCTION__ );
			}
			return $this;
		}
		
		
		/**
		 * включает поддержку трекбеков и пингов (за блоки не отвечает);
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function trackbacks( $set = true ){
			if( $set ){
				$this->options_ArrayObject()->push( __FUNCTION__ );
			}
			else{
				$this->options_ArrayObject()->unset_value( __FUNCTION__ );
			}
			return $this;
		}
		
		
		/**
		 * блок установки произвольных полей;
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function custom_fields( $set = true ){
			if( $set ){
				$this->options_ArrayObject()->push( 'custom-fields' );
			}
			else{
				$this->options_ArrayObject()->unset_value( 'custom-fields' );
			}
			return $this;
		}
		
		
		/**
		 * блок комментариев (обсуждение);
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function comments( $set = true ){
			if( $set ){
				$this->options_ArrayObject()->push( __FUNCTION__ );
			}
			else{
				$this->options_ArrayObject()->unset_value( __FUNCTION__ );
			}
			return $this;
		}
		
		
		/**
		 * блок атрибутов постоянных страниц (шаблон и древовидная связь записей, древовидность должна быть включена).
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function page_attributes( $set = true ){
			if( $set ){
				$this->options_ArrayObject()->push( __FUNCTION__ );
			}
			else{
				$this->options_ArrayObject()->unset_value( __FUNCTION__ );
			}
			return $this;
		}
		
		
		/**
		 * блок форматов записи, если они включены в теме.
		 * @param bool $set
		 * @return array|Supports|mixed|null
		 */
		public function post_formats( $set = true ){
			if( $set ){
				$this->options_ArrayObject()->push( __FUNCTION__ );
			}
			else{
				$this->options_ArrayObject()->unset_value( __FUNCTION__ );
			}
			return $this;
		}
		
	}