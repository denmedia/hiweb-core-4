<?php

	namespace hiweb\components\Dump;


	use hiweb\core\Paths\PathsFactory;
	
	
	class Dump{


		/**
		 * Выводить структуру заданной переменной
		 * @param      $mixed
		 * @param int  $depth       - установить глубину массивов и объектов
		 * @param bool $showObjects - раскрывать объекты
		 * @return string
		 * @version 1.4
		 */
		static function getHtml_arrayPrint( $mixed, $depth = 6, $showObjects = true ){
			if( $depth < 1 ){
				return '<div class="hiweb-core-dump-the-endless">...</div>';
			}
			$r = '';
			$type_of_var = gettype( $mixed );
			$type_of_var_name = $type_of_var;
			if( array_key_exists( $type_of_var, [
				'array' => '',
				'object' => ''
			] ) ){
				if( $type_of_var == 'object' )
					$type_of_var_name = $type_of_var . ':' . get_class( $mixed );
				$r .= ' <span data-type>[' . $type_of_var_name . ']</span>';
			}
			switch( $type_of_var ){
				case 'array':
					$r .= '<ul>';
					foreach( $mixed as $k => $v ){
						$r .= '<li><span data-key>' . $k . '</span>' . ( self::getHtml_arrayPrint( $v, $depth - 1, $showObjects ) ) . '</li>';
					}
					$r .= '</ul>';
					break;
				case 'object':
					$r .= '<ul>';
					if( $showObjects ){
						foreach( $mixed as $k => $v ){
							$r .= '<li><span data-key>' . $k . '</span>' . ( self::getHtml_arrayPrint( $v, $depth - 1, $showObjects ) ) . '</li>';
						}
					}
					$r .= '</ul>';
					break;
				case 'boolean':
					$r .= ( $mixed ? 'TRUE' : 'FALSE' );
					break;
				case 'null':
					$r .= 'NULL';
					break;
				default:
					$r .= ( trim( $mixed ) == '' ? '<span data-type>пусто</span>' : nl2br( htmlentities( $mixed, ENT_COMPAT, 'UTF-8' ) ) );
					break;
			}
			if( !in_array( gettype( $mixed ), [
				'array',
				'object'
			] ) ){
				$r .= ' <span data-type>' . ( gettype( $mixed ) == 'string' && mb_strlen( $mixed ) == 1 ? '[ord:<b>' . ord( $mixed ) . '</b>]' : '' ) . '[' . $type_of_var_name . ']</span>';
			}
			return "<div class='hiweb-core-dump-the-level'>$r</div>";
		}


		/**
		 * @param mixed $mixed
		 * @param int   $depth
		 * @param bool  $showObjects
		 */
		static function the( $mixed, $depth = 6, $showObjects = true ){
			$css = PathsFactory::get( __DIR__ . '/dump-the.css' );
			?>
			<link rel="stylesheet" href="<?= $css->get_url() ?>"/>
			<div class="hiweb-core-dump-the">
				<?= self::getHtml_arrayPrint( $mixed, $depth, $showObjects ); ?>
			</div>
			<?php
		}


		/**
		 * @param      $mixed
		 * @param bool $echo
		 * @return string
		 */
		static function print_r( $mixed, $echo = true ){
			$R = '<pre>' . print_r( $mixed, true ) . '</pre>';
			if( $echo )
				echo $R;
			return $R;
		}


		static function var_dump( $mixed ){
			var_dump( $mixed );
		}


		/**
		 * Записывает данные `$dataMix` в формате HTML в файл. Это удобно для похоже на собственный лог-файл. Этой функцией можно в течении некоторого времени (установленного параметром `$autoDeleteOldFile`) многократно дозаписать информацию в один и тот же файл для дальнейшего анализа. По умолчанию все записывается в файл `log.html` в корне сайта.
		 * @param        $dataMix           - значения
		 * @param string $filePath          - имя файла дампа
		 * @param bool   $append            - не удалять предыдущие записи
		 * @param int    $autoDeleteOldFile - указать время в секундах, в течении которого старые записи не будут удаляться из файла
		 * @return int
		 */
		static function to_file( $dataMix, $filePath = 'log.html', $append = true, $autoDeleteOldFile = 5 ){
			$filePath = PathsFactory::get_file( $filePath )->get_path();
			if( !file_exists( dirname( $filePath ) ) ){
				file_put_contents( PathsFactory::get_file( 'error.txt' )->get_path(), dirname( $filePath ) . ' => not exists' );
				return false;
			}
			//$returnStr = '<style type="text/css">.sep { border-bottom: 1px dotted #ccc; } .sepLast { margin-bottom: 35px; }</style>';
			$returnStr = '';
			$separatorHtml = '<div class="sep"></div>';
			$returnStr .= date::format() . ' / ' . microtime( true ) . ' / ' . $separatorHtml;
			ob_start();
			self::the( $dataMix );
			$returnStr .= ob_get_clean();
			$returnStr .= $separatorHtml;
			$fileContent = '';
			if( file_exists( $filePath ) && is_file( $filePath ) ){
				$time = time();
				$filetime = filemtime( $filePath );
				$timeDelta = $time - $filetime;
				if( $autoDeleteOldFile === false || $timeDelta > $autoDeleteOldFile ){
					unlink( $filePath );
					$returnStr = '<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>' . date::format() . '</title>
 </head>' . $returnStr;
				} else {
					$fileContent = file_get_contents( $filePath );
				}
			}
			///
			$returnStr = ( $append ? $fileContent . $returnStr : $returnStr . $fileContent ) . '<div class="sepLast"></div>';
			return file_put_contents( $filePath, $returnStr );
		}
	}