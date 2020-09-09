<?php
	
	namespace hiweb\core\ArrayObject;
	
	
	class ArrayObject extends \ArrayObject{
		
		/**
		 * @var array
		 */
		private $array;
		/** @var ArrayObject_Json */
		private $Json;
		/** @var ArrayObject_Rows */
		private $Rows;
		
		
		public function __construct( $input = [], $flags = 0, $iterator_class = "ArrayIterator" ){
			if( $input instanceof ArrayObject ){
				$this->array = $input->get();
			}
			else{
				$this->array = (array)$input;
			}
			parent::__construct( $this->array, $flags, $iterator_class );
			ArraysRowsFactory::$latestCreated_ArrayObject = $this;
		}
		
		
		public function __clone(){
			if( $this->Json instanceof ArrayObject_Json ){
				$this->Json = new ArrayObject_Json( $this );
			}
			if( $this->Rows instanceof ArrayObject_Rows ){
				$this->Rows = new ArrayObject_Rows( $this );
			}
		}
		
		
		/**
		 * @param null $array
		 * @return ArrayObject
		 */
		static function get_instance( $array = null ){
			return new ArrayObject( $array );
		}
		
		
		/**
		 * @return string
		 */
		public function __toString(){
			return htmlentities( $this->json()->JSON_PRETTY_PRINT()->get() );
		}
		
		
		/**
		 * @param string|int          $key
		 * @param null|mixed|callable $defaultOrCallable
		 * @param array|mixed         $callableArgs
		 * @return mixed|null
		 */
		public function __invoke( $key, $defaultOrCallable = null, $callableArgs = [] ){
			return $this->get_value( $key, $defaultOrCallable, $callableArgs );
		}
		
		
		/**
		 * @param $name
		 * @return bool
		 */
		public function __isset( $name ){
			return $this->is_key_exists( $name );
		}
		
		
		/**
		 * @param $name
		 * @return ArrayObject
		 */
		public function __unset( $name ){
			return $this->unset_key( $name );
		}
		
		
		/**
		 * @param $name
		 * @return mixed|null
		 */
		public function __get( $name ){
			return $this->get_value( $name );
		}
		
		
		/**
		 * @param $name
		 * @param $value
		 */
		public function __set( $name, $value ){
			$this->set_value( $name, $value );
		}
		
		
		/**
		 * @return ArrayObject_Json
		 */
		public function json(){
			if( !$this->Json instanceof ArrayObject_Json ) $this->Json = new ArrayObject_Json( $this );
			return $this->Json;
		}
		
		
		/**
		 * @return ArrayObject_Rows
		 */
		public function rows(){
			if( !$this->Rows instanceof ArrayObject_Rows ) $this->Rows = new ArrayObject_Rows( $this );
			return $this->Rows;
		}
		
		
		/**
		 * Return original array
		 * @return array
		 */
		public function get(){
			return $this->array;
		}
		
		
		/**
		 * @return array
		 */
		public function get_keys(){
			return array_keys( $this->get() );
		}
		
		
		/**
		 * @param string|int          $key
		 * @param null|mixed|callable $defaultOrCallable
		 * @param array|mixed         $callableArgs
		 * @return mixed|null
		 */
		public function get_value( $key, $defaultOrCallable = null, $callableArgs = [] ){
			return $this->value_by_key( $key, $defaultOrCallable, $callableArgs );
		}
		
		
		/**
		 * @param      $index
		 * @param null $default
		 * @return mixed|null
		 */
		public function get_value_by_index( $index, $default = null ){
			$values = array_values( $this->get() );
			if( !array_key_exists( $index, $values ) ) return $default;
			return $values[ $index ];
		}
		
		
		/**
		 * @param null $default
		 * @return mixed|null
		 */
		public function get_value_first( $default = null ){
			return $this->get_value_by_index( 0, $default );
		}
		
		
		/**
		 * @param null $default
		 * @return mixed|null
		 */
		public function get_value_last( $default = null ){
			return $this->get_value_by_index( - 1, $default );
		}
		
		
		/**
		 * Get array value by key, or return default value by mixed or callable
		 * @param null|string|int     $key
		 * @param null|mixed|callable $defaultOrCallable
		 * @param array|mixed         $callableArgs
		 * @return array|mixed|null
		 */
		public function _( $key = null, $defaultOrCallable = null, $callableArgs = [] ){
			if( is_null( $key ) ) return $this->get();
			else return $this->get_value( $key, $defaultOrCallable, $callableArgs );
		}
		
		
		/**
		 * @return int
		 */
		public function count(){
			return count( $this->array );
		}
		
		
		/**
		 * @return bool
		 */
		public function is_empty(){
			return $this->count() < 1;
		}
		
		
		/**
		 * @param int|string $key
		 * @return bool
		 */
		public function has_key( $key ){
			return array_key_exists( $key, $this->array );
		}
		
		
		/**
		 * @param int  $index
		 * @param bool $return_index - if FALSE then return bool, or return calculate index
		 * @return bool
		 */
		public function has_index( $index, $return_index = false ){
			$index = intval( $index );
			if( $index < 0 ){
				$index = abs( $index );
				$index = (int)( $this->count() - ( $index ) );
			}
			$R = array_key_exists( $index, array_values( $this->array ) );
			if( $return_index && $R ) return $index;
			return $R;
		}
		
		
		/**
		 * Поиск значения ключа, включая вложенные массивы
		 * @param int|string|array    $key
		 * @param null|mixed|callable $defaultOrCallable - default value, if current value is not exists, or callable function
		 * @param array|mixed         $callableArgs      - array of arguments for callable default function
		 * @return mixed|null
		 * @version 1.1
		 */
		public function value_by_key( $key, $defaultOrCallable = null, $callableArgs = [] ){
			if( !is_array( $key ) || count( $key ) == 1 ){
				$key = is_array( $key ) ? reset( $key ) : $key;
				if( $this->has_key( $key ) ){
					return $this->array[ $key ];
				}
				elseif( !is_string( $defaultOrCallable ) && !is_array( $defaultOrCallable ) && is_callable( $defaultOrCallable ) ){
					if( !is_array( $callableArgs ) ) $callableArgs = [ $callableArgs ];
					return call_user_func_array( $defaultOrCallable, $callableArgs );
				}
				else{
					return $defaultOrCallable;
				}
			}
			elseif( is_array( $key ) ){
				foreach( $key as $subkey ){
					if( $this->has_key( $subkey ) ){
						return ( new ArrayObject( $this->array[ $subkey ] ) )->value_by_key( array_slice( $key, 1 ), $defaultOrCallable, $callableArgs );
					}
					else break;
				}
			}
			return $defaultOrCallable;
		}
		
		
		/**
		 * Возвращает значение ключа по его индексу, включая вложенные массивы
		 * @param int|array  $index   = номер (массив номеров) индекса значения. Напрмиер 0 - первый ключ. Чтобы получить последний ключ, укажите -1, так же -2 вернет предпоследний ключ, если таковые имеются.
		 * @param null|mixed $default - в случае, если значение ключа не найдено
		 * @return mixed
		 */
		public function value_by_index( $index, $default = null ){
			if( !is_array( $index ) || count( $index ) == 1 ){
				$index = ( is_array( $index ) && count( $index ) == 1 ) ? intval( reset( $index ) ) : intval( $index );
				return $this->has_index( $index ) ? array_values( $this->array )[ $index ] : $default;
			}
			elseif( is_array( $index ) ){
				foreach( $index as $ind ){
					$ind = intval( $ind );
					if( $this->has_index( $ind ) ){
						return ( new ArrayObject( array_values( $this->array )[ (int)$ind ] ) )->value_by_index( array_slice( $index, 1 ), $default );
					}
					else break;
				}
			}
			return $default;
		}
		
		
		/**
		 * Поместить значение в массив на определенную виртуальную позицию, указав ключ
		 * @param mixed       $value
		 * @param null|int    $index
		 * @param null|string $key
		 * @return array
		 */
		public function push_value( $value, $index = null, $key = null ){
			if( !is_array( $this->array ) ) $this->array = [ $this->array ];
			if( $index === false ){
				$this->array = array_reverse( $this->array );
				if( is_numeric( $key ) || is_string( $key ) ) $this->array[ $key ] = $value;
				else $this->array[] = $value;
				$this->array = array_reverse( $this->array );
			}
			elseif( is_numeric( $index ) ){
				$R = [];
				$n = 0;
				$index = intval( $index );
				if( $index < 0 ){
					$this->array = array_reverse( $this->array );
				}
				$pushed = false;
				foreach( $this->array as $k => $v ){
					if( $n == abs( $index ) ){
						if( is_numeric( $key ) || is_string( $key ) ) $R[ $key ] = $value;
						else $R[] = $value;
						$pushed = true;
					}
					if( array_key_exists( $k, $R ) ){
						if( is_int( $k ) ) $R[ intval( $k ) + 1 ] = $v;
					}
					else $R[ $k ] = $v;
					$n ++;
				}
				if( !$pushed ){
					if( is_numeric( $key ) || is_string( $key ) ) $R[ $key ] = $value;
					else $R[] = $value;
				}
				$this->array = $R;
				if( $index < 0 ){
					$this->array = array_reverse( $this->array );
				}
			}
			else{
				if( is_numeric( $key ) || is_string( $key ) ) $this->array[ $key ] = $value;
				else $this->array[] = $value;
			}
			return $this->array;
		}
		
		
		/**
		 * Push value to current array, include some keys in array, like
		 * @param $key_or_keyArray_or_value
		 * @param $value
		 * @version 2.0
		 */
		public function push( $key_or_keyArray_or_value, $value = null ){
			if( is_null( $value ) ){
				$this->array[] = $key_or_keyArray_or_value;
			}
			else{
				if( is_array( $key_or_keyArray_or_value ) ){
					foreach( array_reverse( $key_or_keyArray_or_value ) as $key ){
						$this->array = $this->push_by_array_keys( $key_or_keyArray_or_value, $value );
					}
				}
				else{
					$this->array[ $key_or_keyArray_or_value ] = $value;
				}
			}
		}
		
		
		private function push_by_array_keys( $keys = [], $value = null ){
			if( !is_array( $keys ) ) $keys = [ $keys ];
			$current_key = array_pop( $keys );
			$value = [ $current_key => $value ];
			if( count( $keys ) > 0 ){
				return $this->push_by_array_keys( $keys, $value );
			}
			else{
				return $value;
			}
		}
		
		
		/**
		 * @return mixed
		 */
		public function pop(){
			$value = array_pop( $this->array );
			return $value;
		}
		
		
		/**
		 * @param array $array
		 * @return $this
		 */
		public function set( $array = [] ){
			$this->array = (array)$array;
			return $this;
		}
		
		
		/**
		 * @param string $glue
		 * @return string
		 */
		public function join( $glue = '' ){
			return join( $glue, $this->get() );
		}
		
		
		/**
		 * Set value in arrays, by key
		 * @param null $key - leave NULL, if you want push
		 * @param null $value
		 */
		public function set_value( $key = null, $value = null ){
			if( is_null( $key ) ) $this->array[] = $value;
			else $this->array[ $key ] = $value;
		}
		
		
		/**
		 * @return mixed
		 */
		public function shift(){
			$value = array_shift( $this->array );
			return $value;
		}
		
		
		/**
		 * @param      $keyOrValue
		 * @param null $value
		 */
		public function unshift( $keyOrValue, $value = null ){
			$this->array = array_reverse( $this->array, true );
			if( is_null( $value ) ){
				$this->array[] = $keyOrValue;
			}
			else{
				$this->array[ $keyOrValue ] = $value;
			}
			$this->array = array_reverse( $this->array, true );
		}
		
		
		/**
		 * Функция двигает (меняет индекс) элемента массива
		 * @param int|string $source_key
		 * @param int        $destination_index
		 * @return array
		 */
		public function move_value( $source_key, $destination_index ){
			if( !$this->has_key( $source_key ) ) return $this->array;
			$item = $this->array[ $source_key ];
			unset( $this->array[ $source_key ] );
			return $this->push_value( $item, $destination_index, $source_key );
		}
		
		
		/**
		 * Поиск ключа (ключей) по его начению
		 * @param      $search_value
		 * @param bool $use_regexp
		 * @param null $default
		 * @return array|int|null|string
		 */
		public function key_by_value( $search_value, $use_regexp = false, $default = null ){
			foreach( $this->array as $key => $val ){
				if( !is_array( $val ) && !is_object( $val ) ){
					if( $search_value == $val || ( $use_regexp && preg_match( $search_value, $val ) > 0 ) ){
						return $key;
					}
				}
				else{
					$sub_find = ( new ArrayObject( $val ) )->key_by_value( $search_value, $use_regexp );
					if( !is_null( $sub_find ) ){
						return array_merge( [ $key ], is_array( $sub_find ) ? $sub_find : [ $sub_find ] );
					}
				}
			}
			return $default;
		}
		
		
		/**
		 * @param $value
		 * @return bool
		 */
		public function has_value( $value ){
			$haystack = @array_flip( $this->array );
			if( array_key_exists( $value, $haystack ) ) return true;
			return false;
		}
		
		
		/**
		 * @param $needle
		 * @alias self::has_value
		 * @return bool
		 */
		public function in( $needle ){
			return $this->has_value( $needle );
		}
		
		
		/**
		 * @param $key
		 * @return ArrayObject
		 */
		public function unset_key( $key ){
			$new_array = $this->array;
			if( !is_array( $key ) || count( $key ) == 1 ){
				$key = is_array( $key ) ? reset( $key ) : $key;
				unset( $new_array[ $key ] );
			}
			else{
				$first_key = array_shift( $key );
				$new_array[ $first_key ] = ( new ArrayObject( $new_array[ $first_key ] ) )->unset_key( $key );
			}
			$this->array = $new_array;
			return $this;
		}
		
		
		/**
		 * @param $value
		 * @return ArrayObject
		 */
		public function unset_value( $value ){
			$keys = $this->key_by_value( $value );
			return $this->unset_key( $keys );
		}
		
		
		/**
		 * @param null $needle_key
		 * @return bool|string
		 */
		public function free_key( $needle_key = null ){
			for( $count = 0; $count < 999; $count ++ ){
				$count = sprintf( '%03u', $count );
				$input_name_id = $needle_key . '_' . $count;
				if( !isset( $this->array[ $input_name_id ] ) ) return $input_name_id;
			}
			return false;
		}
		
		
		/**
		 * @param      $mixedOrArray
		 * @param bool $low_priority
		 * @return ArrayObject
		 */
		public function merge( $mixedOrArray, $low_priority = false ){
			if( is_array( $mixedOrArray ) ){
				if( $low_priority ){
					$this->array = array_merge( $mixedOrArray, $this->get() );
				}
				else{
					$this->array = array_merge( $this->get(), $mixedOrArray );
				}
			}
			else{
				$new_array = $this->get();
				if( $low_priority ) array_unshift( $new_array, $mixedOrArray );
				else array_push( $new_array, $mixedOrArray );
			}
			return $this;
		}
		
		
		/**
		 * Return string like `color="#000" background="#fff"`
		 * @param bool $return_array_pairs
		 * @return array|string
		 */
		public function get_param_html_tags( $return_array_pairs = false ){
			$pairs = [];
			foreach( $this->get() as $key => $val ){
				$pairs[] = $key . '="' . htmlentities( is_array($val) ? json_encode($val) : $val, ENT_QUOTES, 'UTF-8' ) . '"';
			}
			return $return_array_pairs ? $pairs : implode( ' ', $pairs );
		}
		
		
		/**
		 * Return string like `color: #000; background: #fff`
		 * @return string
		 */
		public function get_param_html_style(){
			$R = [];
			foreach( $this->get() as $key => $val ){
				$R[] = $key . ':' . addslashes( $val );
			}
			return join( ';', $R );
		}
		
		
		/**
		 * Return string like `color=#000&background=#fff`
		 * @param bool $return_null_params
		 * @return string
		 */
		public function get_param_url( $return_null_params = false ){
			$pairs = [];
			foreach( $this->get() as $key => $val ){
				if( !$return_null_params && is_null( $val ) ) continue;
				$pairs[] = urlencode( $key ) . '=' . urlencode( is_array( $val ) ? json_encode( $val ) : $val );
			}
			return join( '&', $pairs );
		}
		
		
		/**
		 * Return string like `color=#000&background=#fff`
		 * @param bool $return_null_params
		 * @return string
		 */
		public function get_params_url( $return_null_params = false ){
			return $this->get_param_url( $return_null_params );
		}
		
		
		/**
		 * @param $key
		 * @return bool
		 */
		public function key_exists( $key ){
			return array_key_exists( $key, $this->array );
		}
		
		
		/**
		 * @aliace \hiweb\core\arrays\Arrays::key_exists
		 * @param $key
		 * @return bool
		 */
		public function is_key_exists( $key ){
			return $this->key_exists( $key );
		}
		
		
		/**
		 * @param $key
		 * @param $new_key
		 * @return bool
		 */
		public function key_rename( $key, $new_key ){
			if( $this->key_exists( $key ) ){
				$value = $this->array[ $key ];
				unset( $this->array[ $key ] );
				$this->array[ $new_key ] = $value;
				return true;
			}
			return false;
		}
		
		
		/**
		 * Return free key in array
		 * @param null $desired_key - desired key
		 * @return bool|string
		 */
		public function get_free_key( $desired_key = null ){
			for( $count = 0; $count < 999; $count ++ ){
				$count = sprintf( '%03u', $count );
				$input_name_id = $desired_key . '_' . $count;
				if( !$this->key_exists( $input_name_id ) ) return $input_name_id;
			}
			return false;
		}
		
		
		
		///DEPRECATED
		
		
		/**
		 * @return bool
		 * @deprecated
		 */
		public function have_rows(){
			return $this->rows()->have();
		}
		
		
		/**
		 * @return mixed|null
		 * @deprecated
		 */
		public function the_row(){
			return $this->rows()->the();
		}
		
		
		/**
		 * @return ArrayObject|null
		 * @deprecated
		 */
		public function get_current_row(){
			return $this->rows()->get_current();
		}
		
		
		/**
		 * @param      $key
		 * @param null $default
		 * @return ArrayObject|null
		 * @deprecated
		 */
		public function get_sub_field( $key, $default = null ){
			return $this->rows()->get_sub_field( $key, $default );
		}
		
		
	}