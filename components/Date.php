<?php

	namespace hiweb\components;


	class Date{

		static $convert_format_keys_to_localized = [
			//День
			'd' => '%d', //День месяца, 2 цифры с ведущим нулём	от 01 до 31
			'D' => '%a', //Текстовое представление дня недели, 3 символа	от Mon до Sun
			'j' => '%e', //День месяца без ведущего нуля	от 1 до 31
			'z' => '', //Порядковый номер дня в году (начиная с 0)	От 0 до 365
			'S' => '', //Английский суффикс порядкового числительного дня месяца, 2 символа	st, nd, rd или th. Применяется совместно с j
			///Неделя
			'l' => '%A', //Полное наименование дня недели	от Sunday до Saturday
			'N' => '%u', //Порядковый номер дня недели в соответствии со стандартом ISO-8601 (добавлено в PHP 5.1.0)	от 1 (понедельник) до 7 (воскресенье)
			'w' => '%w', //Порядковый номер дня недели	от 0 (воскресенье) до 6 (суббота)
			'W' => '%V', //Порядковый номер недели в указанном году в соответствии со стандартом ISO-8601:1988, счет начинается с той недели, которая содержит минимум 4 дня, неделя начинается с понедельника	От 01 до 53 (где 53 указывает на перекрывающуюся неделю)
			///Месяц
			'F' => '%B', //Полное название месяца, в соответствии с настройками локали	От January до December
			'm' => '%m', //Двухзначный порядковый номер месяца	От 01 (январь) до 12 (декабрь)
			'M' => '%h', //Аббревиатура названия месяца, в соответствии с настройками локали (псевдоним %b)	От Jan до Dec
			'n' => '', //Порядковый номер месяца без ведущего нуля	от 1 до 12
			't' => '', //Количество дней в указанном месяце	от 28 до 31
			'L' => '', //Признак високосного года	1, если год високосный, иначе 0.
			///Год
			'o' => '%G', //Номер года в соответствии со стандартом ISO-8601. Имеет то же значение, что и Y, кроме случая, когда номер недели ISO (W) принадлежит предыдущему или следующему году; тогда будет использован год этой недели. (добавлено в PHP 5.1.0)	Примеры: 1999 или 2003
			'Y' => '%Y', //Четырехзначный номер года	Пример: 2038
			'y' => '%y', //Двухзначный порядковый номер года	Пример: 09 для 2009, 79 для 1979
			///Время
			'a' => '%P', //'am' или 'pm' в зависимости от указанного времени	Пример: am для 00:31, pm для 22:23
			'A' => '%p', //'AM' или 'PM' в верхнем регистре, в зависимости от указанного времени	Пример: AM для 00:31, PM для 22:23
			'B' => '', //Время в формате Интернет-времени (альтернативной системы отсчета времени суток)	от 000 до 999
			'g' => '%l ', //Час в 12-часовом формате, с пробелом перед одиночной цифрой	От 1 до 12
			'G' => '%k', //Часы в 24-часовом формате без ведущего нуля	от 0 до 23
			'h' => '%I', //Часы в 12-часовом формате с ведущим нулём	от 01 до 12
			'H' => '%H', //Двухзначный номер часа в 24-часовом формате	От 00 до 23
			'i' => '%M', //Двухзначный номер минуты	От 00 до 59
			's' => '%S', //Двухзначный номер секунды	От 00 до 59
			'u' => '', //Микросекунды (добавлено в PHP 5.2.2). Учтите, что date() всегда будет возвращать 000000, т.к. она принимает целочисленный параметр, тогда как DateTime::format() поддерживает микросекунды, если DateTime создан с ними.
			'v' => '' //Миллисекунды (добавлено в PHP 7.0.0). Замечание такое же как и для u.	Пример: 654
		];


		static private $timezone;


		static function detect_timezone(){
			if( !is_string( self::$timezone ) ){
				self::$timezone = 'UTC';
				if( is_link( '/etc/localtime' ) ){
					// Mac OS X (and older Linuxes)
					// /etc/localtime is a symlink to the
					// timezone in /usr/share/zoneinfo.
					$filename = readlink( '/etc/localtime' );
					if( strpos( $filename, '/usr/share/zoneinfo/' ) === 0 ){
						self::$timezone = substr( $filename, 20 );
					}
				} elseif( file_exists( '/etc/timezone' ) ) {
					// Ubuntu / Debian.
					$data = file_get_contents( '/etc/timezone' );
					if( $data ){
						self::$timezone = $data;
					}
				} elseif( file_exists( '/etc/sysconfig/clock' ) ) {
					// RHEL / CentOS
					$data = parse_ini_file( '/etc/sysconfig/clock' );
					if( !empty( $data['ZONE'] ) ){
						self::$timezone = $data['ZONE'];
					}
				}

				date_default_timezone_set( self::$timezone );
			}

			return self::$timezone;
		}


		/**
		 * @return int
		 */
		static function time(){
			return intval( function_exists( 'current_time' ) ? current_time( 'timestamp' ) : time() );
		}


		/**
		 * Возвращает форматированное дату и время
		 * @param int    $time   - необходимое время в секундах, если не указывать, будет взято текущее время
		 * @param string $format - форматирование времени
		 * @return bool|string
		 */
		static function format( $time = null, $format = 'Y-m-d H:i:s' ){
			$time = intval( $time );
			if( $time < 100 ){
				$time = self::time();
			}
			return date( $format, $time );
		}


		/**
		 * Возвращает наименование дня недели
		 * @param int  $weekNum
		 * @param bool $fullName
		 * @return bool
		 */
		static function week( $weekNum = 0, $fullName = true ){
			$weekNum = intval( $weekNum );
			$a = [
				[ 'вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб' ],
				[ 'восресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота' ]
			];

			return isset( $a[ $fullName ? 1 : 0 ][ $weekNum ] ) ? $a[ $fullName ? 1 : 0 ][ $weekNum ] : false;
		}


		/**
		 * Convert date format string to localize, etc.: 'Y m d' to '%Y %m %d'
		 * @param string $format
		 * @return string
		 */
		static function formatToLocalize( $format = 'Y m d' ){
			return strtr( $format, self::$convert_format_keys_to_localized );
		}


	}