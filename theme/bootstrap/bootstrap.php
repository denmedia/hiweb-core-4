<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 22:15
	 */

	namespace theme;


	class bootstrap{

		private static $cols_to_class_double = [
			'1.1' => 'col-12',
			'1.2' => 'col-lg-6',
			'1.3' => 'col-lg-4',
			'1.4' => 'col-md-3',
			'1.6' => 'col-md-2',
			'1.12' => 'col-md-1',
			'2.2' => 'col-6',
			'2.3' => 'col-md-6 col-lg-4',
			'2.4' => 'col-md-6 col-lg-3',
			'2.6' => 'col-md-6 col-lg-2',
			'2.12' => 'col-md-6 col-lg-1',
			'3.3' => 'col-4',
			'3.4' => 'col-sm-4 col-lg-3',
			'3.6' => 'col-md-4 col-lg-2',
			'3.12' => 'col-md-4 col-lg-1',
			'4.4' => 'col-3',
			'4.6' => 'col-sm-3 col-lg-2',
			'4.12' => 'col-sm-3 col-lg-1',
			'6.6' => 'col-2',
			'6.12' => 'col-sm-2 col-lg-1',
			'12.12' => 'col-1'
		];

		private static $cols_to_class_simple = [
			1 => 'col-md-12',
			2 => 'col-md-6',
			3 => 'col-md-6 col-lg-4',
			4 => 'col-sm-6 col-lg-3',
			5 => 'col-sm-6 col-lg-3',
			6 => 'col-sm-6 col-md-4 col-lg-2',
			7 => 'col-sm-6 col-md-4 col-lg-2',
			8 => 'col-sm-6 col-md-4 col-lg-2'
		];

		private static $cols_to_class_simple_double = [
			1 => 'col-md-12',
			2 => 'col-md-12',
			3 => 'col-md-12 col-lg-8',
			4 => 'col-md-12 col-lg-6',
			5 => 'col-md-12 col-lg-6',
			6 => 'col-sm-12 col-md-8 col-lg-4',
			7 => 'col-sm-12 col-md-8 col-lg-4',
			8 => 'col-sm-12 col-md-8 col-lg-4'
		];


		/**
		 * @param int $xl
		 * @param int $lg
		 * @param int $md
		 * @param int $sm
		 * @param int $xs
		 * @return string
		 */
		static function cols_to_class( $xl = 6, $lg = 4, $md = 3, $sm = 2, $xs = 1 ){
			$cols_data = get_defined_vars();
			$classes = [];
			foreach( $cols_data as $name => $cols ){
				if( is_numeric( $cols ) ){
					if( $name == 'xs' ) $name = '';
					$classes[] = 'col-' . ( $name == '' ? '' : ( $name . '-' ) ) . round( 12 / $cols );
				}
			}
			return implode( ' ', $classes );
		}


		/**
		 * @param int  $cols - from 1 to 8 infinity...
		 * @param bool $return_double
		 * @return mixed
		 */
		static function cols_to_class_simple( $cols = 4, $return_double = false ){
			return $return_double ? self::$cols_to_class_simple_double[ $cols ] : self::$cols_to_class_simple[ $cols ];
		}


		/**
		 * @param int $full
		 * @param int $small
		 * @return mixed
		 */
		static function cols_to_class_double( $full = 4, $small = 2 ){
			return self::$cols_to_class_double[ $small . '.' . $full ];
		}


		/**
		 * @param int  $cols
		 * @param int  $count_all_cols
		 * @return string[]
		 */
		static function cols_to_class_by_rows( $cols = 4, $count_all_cols = 9){
			if( $cols < 1 ) $cols = 1;
			$R = [];
			for( $n = 0; $n <= ceil( $count_all_cols / $cols ); $n ++ ){
				$cols_in_row = $count_all_cols >= $cols ? $cols : $count_all_cols;
				$count_all_cols -= $cols_in_row;
				$R = array_merge( $R, array_fill( 0, $cols_in_row, self::cols_to_class_simple( $cols_in_row ) ) );
			}
			return $R;
		}

	}