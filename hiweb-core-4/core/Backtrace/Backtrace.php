<?php

	namespace hiweb\core\Backtrace;


	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\Paths\PathsFactory;


	class Backtrace{

		/**
		 * @param int $depth
		 * @return point
		 */
		static function Point( $depth = 0 ){
			return new Point( $depth );
		}


		/**
		 * Возвращает массив
		 * @param bool   $class            - возвращать классы className
		 * @param bool   $functions        - возвращать имена функций functionName
		 * @param bool   $files            - возвращать имена файлов fileName
		 * @param bool   $dirs             - возвращать имена папки, из которой вызван файл
		 * @param bool   $paths            - возвращать путь папки с файлом
		 * @param bool   $returnChunkArray - возвращать разбитый массив на ключевые значения array('class' => ..., 'functions' => ..., 'file' => ...)
		 * @param int    $minDepth         - минимальная глубина
		 * @param int    $maxDepth         - максимальная глубина
		 * @param string $prepend          - добавлять до значения
		 * @param string $append           - добавлять после каждого значения
		 * @param bool   $args             - возвращать аргументы
		 * @return array
		 * @version 1.2
		 */
		static function get_byGroup( $class = true, $functions = true, $files = true, $dirs = true, $paths = true, $returnChunkArray = false, $minDepth = 2, $maxDepth = 4, $prepend = '', $append = '', $args = false ){
			$R = [];
			$dbt = debug_backtrace();
			$n = 0;
			foreach( $dbt as $i ){
				$n ++;
				if( $n < $minDepth || $n > $maxDepth ){
					continue;
				}
				if( $class && isset( $i['class'] ) ){
					$R['className'][] = $prepend . $i['class'] . $append;
				}
				if( $args && isset( $i['args'] ) ){
					$R['args'][] = $i['args'];
				}
				if( $functions && isset( $i['function'] ) ){
					$R['functionName'][] = $prepend . $i['function'] . $append;
				}
				if( $files && isset( $i['file'] ) ){
					$R['fileName'][] = $prepend . basename( $i['file'] ) . $append;
				}
				if( $dirs && isset( $i['file'] ) ){
					$R['dirName'][] = $prepend . basename( dirname( $i['file'] ) ) . $append;
					if( $n < $maxDepth ){
						$R['dirName'][] = $prepend . basename( dirname( dirname( $i['file'] ) ) ) . $append;
					}
				}
				if( $paths && isset( $i['file'] ) ){
					$R['path'][] = str_replace( '\\', '/', $prepend . dirname( $i['file'] ) . $append );
					if( $n < $maxDepth ){
						$R['path'][] = str_replace( '\\', '/', $prepend . dirname( dirname( $i['file'] ) ) . $append );
					}
				}
			}
			foreach( $R as $k => $i ){
				$R[ $k ] = array_unique( $i );
			}
			if( !$returnChunkArray ){
				$R2 = [];
				foreach( $R as $i ){
					if( is_array( $i ) ){
						$R2 = $R2 + $i;
					}
				}

				return $R2;
			}

			return $R;
		}


		/**
		 * Возвращает путь и строку файла, откуда была запущена функция
		 * @param int $depth - глубина родительских функций
		 * @return string
		 * @version 2.0
		 */
		static function file_locate( $depth = 0 ){
			$debugBacktrace = debug_backtrace();
			$R = '';
			$array = new ArrayObject( $debugBacktrace );
			if( $array->count() < $depth ){
				//hiweb()->console()->warn( 'Слишком глубоко [' . $depth . ']', 1 );
			} else {
				$R = realpath( $array( [ $depth, 'file' ], ':файл не найден:' ) ) . ' : ' . $array( [ $depth, 'line' ] );
			}

			return $R;
		}


		/**
		 * Возвращает функцию, откуда была запущена текущая функция
		 * @param int $depth
		 * @return string
		 */
		static function function_trace( $depth = 0 ){
			return ''; //TODO!
			//			$debugBacktrace = debug_backtrace();
			//			$class = arrays::get_byKey( $debugBacktrace, [ $depth, 'class' ], '' );
			//			$function = arrays::get_byKey( $debugBacktrace, [ $depth, 'function' ], '' );
			//			$type = arrays::get_byKey( $debugBacktrace, [ $depth, 'type' ], '' );
			//			//Class filter
			//			if( strpos( $class, 'hiweb_' ) === 0 && method_exists( hiweb(), substr( $class, 6 ) ) ){
			//				$r = 'hiweb->' . substr( $class, 6 );
			//			} else {
			//				$r = $class;
			//			}
			//			$r .= $type . $function;
			//
			//			return $r;
		}


		/**
		 * @param int  $depth
		 * @param null $arg_index
		 * @return null
		 */
		static function get_args( $depth = 0, $arg_index = null ){
			if( !is_numeric( $depth ) ) return null;
			$debugBacktrace = debug_backtrace();
			$depth += 1;
			if( !isset( $debugBacktrace[ $depth ] ) ) return null;
			///
			$args = isset( $debugBacktrace[ $depth ]['args'] ) ? $debugBacktrace[ $depth ]['args'] : null;
			if( !is_array( $args ) ) return null;
			///
			if( is_numeric( $arg_index ) ){
				return array_key_exists( $arg_index, $args ) ? $args[ $arg_index ] : null;
			} else {
				return $args;
			}
		}


		/**
		 * @param int  $start
		 * @param int  $offset
		 * @param bool $reverse
		 * @return array
		 */
		static function get_resume( $start = 0, $offset = 10, $reverse = false ){
			$R = [];
			$start ++;
			$debugBacktrace = debug_backtrace();
			if( is_array( $debugBacktrace ) ) foreach( $debugBacktrace as $index => $items ){
				if( $index >= $start && $index < ( $start + $offset ) ){
					if( isset( $items['class'] ) ){
						if( isset( $items['file'] ) ){
							$R[] = $items['class'] . $items['type'] . $items['function'] . '() ➜ ' . PathsFactory::get( $items['file'] )->get_path_relative() . ' ⇶ ' . $items['line'];
						} else {
							$R[] = $items['class'] . $items['type'] . $items['function'] . '()';
						}
					}
				}
			}
			if( $reverse ) $R = array_reverse( $R );
			return $R;
		}
	}