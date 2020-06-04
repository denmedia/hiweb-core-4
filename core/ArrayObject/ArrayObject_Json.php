<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 07/12/2018
	 * Time: 10:32
	 */

	namespace hiweb\core\ArrayObject;


	class ArrayObject_Json{

		/** @var ArrayObject */
		private $ArrayObject;
		/** @var int */
		private $depth = 512;
		/** @var array */
		private $option = [];


		public function __construct( ArrayObject $Array ){
			$this->ArrayObject = $Array;
		}


		/**
		 * Получить строку текущего JSON
		 * @return string
		 */
		public function get(){
			return json_encode( $this->ArrayObject->get(), array_sum( $this->option ), $this->depth );
		}


		/**
		 * @return string
		 */
		public function __toString(){
			return $this->get();
		}


		/**
		 * @param $depth
		 * @return $this
		 */
		public function set_depth( $depth ){
			$this->depth = $depth;
			return $this;
		}


		/**
		 * Все < и > кодируются в \u003C и \u003E. Доступно с PHP 5.3.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_HEX_TAG(){
			$this->option[] = JSON_HEX_TAG;
			return $this;
		}


		/**
		 * Все & кодируются в \u0026. Доступно с PHP 5.3.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_HEX_AMP(){
			$this->option[] = JSON_HEX_AMP;
			return $this;
		}


		/**
		 * Все символы ' кодируются в \u0027. Доступно с PHP 5.3.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_HEX_APOS(){
			$this->option[] = JSON_HEX_APOS;
			return $this;
		}


		/**
		 * Все символы " кодируются в \u0022. Доступно с PHP 5.3.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_HEX_QUOT(){
			$this->option[] = JSON_HEX_QUOT;
			return $this;
		}


		/**
		 * Выдавать объект вместо массива при использовании неассоциативного массива. Это полезно, когда принимающая программа или код ожидают объект, а массив пуст. Доступно с PHP 5.3.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_FORCE_OBJECT(){
			$this->option[] = JSON_FORCE_OBJECT;
			return $this;
		}


		/**
		 * Кодирование строк, содержащих числа, как числа. Доступно с PHP 5.3.3.
		 * @return ArrayObject_Json
		 */
		public function JSON_NUMERIC_CHECK(){
			$this->option[] = JSON_NUMERIC_CHECK;
			return $this;
		}


		/**
		 * Использовать пробельные символы в возвращаемых данных для их форматирования. Доступно с PHP 5.4.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_PRETTY_PRINT(){
			$this->option[] = JSON_PRETTY_PRINT;
			return $this;
		}


		/**
		 * Не экранировать /. Доступно с PHP 5.4.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_UNESCAPED_SLASHES(){
			$this->option[] = JSON_UNESCAPED_SLASHES;
			return $this;
		}


		/**
		 * Не кодировать многобайтовые символы Unicode (по умолчанию они кодируются как \uXXXX). Доступно с PHP 5.4.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_UNESCAPED_UNICODE(){
			$this->option[] = JSON_UNESCAPED_UNICODE;
			return $this;
		}


		/**
		 * Позволяет избежать возникновения ошибок при использовании функции json_encode. Осуществляет подстановку значений по умолчанию вместо некодируемых. Доступно с PHP 5.5.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_PARTIAL_OUTPUT_ON_ERROR(){
			$this->option[] = JSON_PARTIAL_OUTPUT_ON_ERROR;
			return $this;
		}


		/**
		 * Гарантирует, что значение типа float будет преобразовано именно в значение типа float в случае, если дробная часть равна 0. Доступно с PHP 5.6.6.
		 * @return ArrayObject_Json
		 */
		public function JSON_PRESERVE_ZERO_FRACTION(){
			$this->option[] = JSON_PRESERVE_ZERO_FRACTION;
			return $this;
		}


		/**
		 * Символы конца строки не будут экранироваться, если задана константа JSON_UNESCAPED_UNICODE. Поведение будет таким же, какое оно было до PHP 7.1 без этой константы. Доступно с PHP 7.1.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_UNESCAPED_LINE_TERMINATORS(){
			if(version_compare( PHP_VERSION, '7.1' ) >= 0){
				$this->option[] = JSON_UNESCAPED_LINE_TERMINATORS;
			}
			return $this;
		}


		/**
		 * Выбрасывается исключение JsonException в случае возникновения ошибок вместо установки глобального состояния ошибки, которое может быть получено с помощью функции json_last_error(). Константа JSON_PARTIAL_OUTPUT_ON_ERROR имеет приоритет над JSON_THROW_ON_ERROR. Доступно с PHP 7.3.0.
		 * @return ArrayObject_Json
		 */
		public function JSON_THROW_ON_ERROR(){
			if(version_compare( PHP_VERSION, '7.3' ) >= 0){
				$this->option[] = JSON_THROW_ON_ERROR;
			}
			return $this;
		}


		/**
		 * To get a really clean json string use these three constants
		 * @return $this
		 */
		public function JSON_UNESCAPED_UNICODE__JSON_UNESCAPED_SLASHES__JSON_NUMERIC_CHECK(){
			$this->option[] = JSON_UNESCAPED_UNICODE;
			$this->option[] = JSON_UNESCAPED_SLASHES;
			$this->option[] = JSON_NUMERIC_CHECK;
			return $this;
		}

	}