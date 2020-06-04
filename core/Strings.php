<?php

	namespace hiweb\core;


	class Strings{


		static function explode_by_symbol( $string ){ return preg_split( '//u', $string, - 1, PREG_SPLIT_NO_EMPTY ); }


		static function rand( $return_col = 20, $in_use_latin = true, $in_use_number = true, $useReg = false ){
			$symb_arr = [];
			$symb_only_latin_arr = [];
			if( $in_use_latin ){
				for( $list_n = ord( 'a' ); $list_n < ord( 'z' ); $list_n ++ ){
					array_push( $symb_arr, $list_n );
					array_push( $symb_only_latin_arr, $list_n );
				}
			}
			if( $in_use_latin and $useReg ){
				for( $list_n = ord( 'A' ); $list_n < ord( 'Z' ); $list_n ++ ){
					array_push( $symb_arr, $list_n );
				}
			}
			if( $in_use_number ){
				for( $list_n = ord( '0' ); $list_n < ord( '9' ); $list_n ++ ){
					array_push( $symb_arr, $list_n );
				}
			}
			$return_key = '';
			for( $list_n = 0; $list_n < $return_col; $list_n ++ ){
				if( $in_use_latin and $list_n == 0 ){
					$return_key .= chr( $symb_only_latin_arr[ \rand( 0, count( $symb_only_latin_arr ) - 1 ) ] );
				} else {
					$return_key .= chr( $symb_arr[ \rand( 0, count( $symb_arr ) - 1 ) ] );
				}
			}

			return $return_key;
		}


		/**
		 * Convert string utf8 to ansii
		 * @param string $utf8
		 * @return string
		 */
		static function utf8_to_ansii( $utf8 ){
			if( function_exists( 'iconv' ) ){
				$returnStr = @iconv( 'UTF-8', 'windows-1251//IGNORE', $utf8 );
			} else {
				$returnStr = strtr( $utf8, [
					"Р°" => "а",
					"Р±" => "б",
					"РІ" => "в",
					"Рі" => "г",
					"Рґ" => "д",
					"Рµ" => "е",
					"С‘" => "ё",
					"Р¶" => "ж",
					"Р·" => "з",
					"Рё" => "и",
					"Р№" => "й",
					"Рє" => "к",
					"Р»" => "л",
					"Рј" => "м",
					"РЅ" => "н",
					"Рѕ" => "о",
					"Рї" => "п",
					"СЂ" => "р",
					"СЃ" => "с",
					"С‚" => "т",
					"Сѓ" => "у",
					"С„" => "ф",
					"С…" => "х",
					"С†" => "ц",
					"С‡" => "ч",
					"С€" => "ш",
					"С‰" => "щ",
					"СЉ" => "ъ",
					"С‹" => "ы",
					"СЊ" => "ь",
					"СЌ" => "э",
					"СЋ" => "ю",
					"СЏ" => "я",
					"Рђ" => "А",
					"Р‘" => "Б",
					"Р’" => "В",
					"Р“" => "Г",
					"Р”" => "Д",
					"Р•" => "Е",
					"РЃ" => "Ё",
					"Р–" => "Ж",
					"Р—" => "З",
					"Р?" => "И",
					"Р™" => "Й",
					"Рљ" => "К",
					"Р›" => "Л",
					"Рњ" => "М",
					"Рќ" => "Н",
					"Рћ" => "О",
					"Рџ" => "П",
					"Р " => "Р",
					"РЎ" => "С",
					"Рў" => "Т",
					"РЈ" => "У",
					"Р¤" => "Ф",
					"РҐ" => "Х",
					"Р¦" => "Ц",
					"Р§" => "Ч",
					"РЁ" => "Ш",
					"Р©" => "Щ",
					"РЄ" => "Ъ",
					"Р«" => "Ы",
					"Р¬" => "Ь",
					"Р­" => "Э",
					"Р®" => "Ю",
					"С–" => "і",
					"Р†" => "І",
					"С—" => "ї",
					"Р‡" => "Ї",
					"С”" => "є",
					"Р„" => "Є",
					"Т‘" => "ґ",
					"Тђ" => "Ґ",
				] );
			}

			return $returnStr;
		}


		/**
		 * Convert ansii to utf-8
		 * @param string $ansii
		 * @return string
		 */
		static function ansii_to_utf8( $ansii ){
			if( function_exists( 'iconv' ) ){
				return iconv( 'windows-1251//IGNORE', 'UTF-8', $ansii );
			} else {
				return strtr( $ansii, array_flip( [
					"Р°" => "а",
					"Р±" => "б",
					"РІ" => "в",
					"Рі" => "г",
					"Рґ" => "д",
					"Рµ" => "е",
					"С‘" => "ё",
					"Р¶" => "ж",
					"Р·" => "з",
					"Рё" => "и",
					"Р№" => "й",
					"Рє" => "к",
					"Р»" => "л",
					"Рј" => "м",
					"РЅ" => "н",
					"Рѕ" => "о",
					"Рї" => "п",
					"СЂ" => "р",
					"СЃ" => "с",
					"С‚" => "т",
					"Сѓ" => "у",
					"С„" => "ф",
					"С…" => "х",
					"С†" => "ц",
					"С‡" => "ч",
					"С€" => "ш",
					"С‰" => "щ",
					"СЉ" => "ъ",
					"С‹" => "ы",
					"СЊ" => "ь",
					"СЌ" => "э",
					"СЋ" => "ю",
					"СЏ" => "я",
					"Рђ" => "А",
					"Р‘" => "Б",
					"Р’" => "В",
					"Р“" => "Г",
					"Р”" => "Д",
					"Р•" => "Е",
					"РЃ" => "Ё",
					"Р–" => "Ж",
					"Р—" => "З",
					"Р?" => "И",
					"Р™" => "Й",
					"Рљ" => "К",
					"Р›" => "Л",
					"Рњ" => "М",
					"Рќ" => "Н",
					"Рћ" => "О",
					"Рџ" => "П",
					"Р " => "Р",
					"РЎ" => "С",
					"Рў" => "Т",
					"РЈ" => "У",
					"Р¤" => "Ф",
					"РҐ" => "Х",
					"Р¦" => "Ц",
					"Р§" => "Ч",
					"РЁ" => "Ш",
					"Р©" => "Щ",
					"РЄ" => "Ъ",
					"Р«" => "Ы",
					"Р¬" => "Ь",
					"Р­" => "Э",
					"Р®" => "Ю",
					"С–" => "і",
					"Р†" => "І",
					"С—" => "ї",
					"Р‡" => "Ї",
					"С”" => "є",
					"Р„" => "Є",
					"Т‘" => "ґ",
					"Тђ" => "Ґ",
				] ) );
			}
		}


		/**
		 * @param string $parseStr
		 * @return array
		 */
		static function explode_to_string_numeric( $parseStr ){
			$r = [];
			foreach( self::explode_by_symbol( $parseStr ) as $s ){
				end( $r );
				$lastVal = current( $r );
				$lastKey = key( $r );
				if( $lastVal === false ){
					$r[] = $s;
				} else {
					$lastNum = is_numeric( $lastVal );
					if( is_numeric( $s ) && $lastNum ){
						$r[ $lastKey ] .= $s;
					} else {
						$r[] = $s;
					}
				}
			}

			return $r;
		}


		/**
		 * Formatting JSON string
		 * @param string $json
		 * @return string
		 */
		static function json_format( $json ){
			if( !is_string( $json ) ){
				$json = json_encode( $json );
			}
			$result = '';
			$pos = 0;
			$strLen = strlen( $json );
			$indentStr = '  ';
			$newLine = "\n";
			$prevChar = '';
			$outOfQuotes = true;
			for( $i = 0; $i <= $strLen; $i ++ ){
				$char = substr( $json, $i, 1 );
				if( $char == '"' && $prevChar != '\\' ){
					$outOfQuotes = !$outOfQuotes;
				} else if( ( $char == '}' || $char == ']' ) && $outOfQuotes ){
					$result .= $newLine;
					$pos --;
					for( $j = 0; $j < $pos; $j ++ ){
						$result .= $indentStr;
					}
				}
				$result .= $char;
				if( ( $char == ',' || $char == '{' || $char == '[' ) && $outOfQuotes ){
					$result .= $newLine;
					if( $char == '{' || $char == '[' ){
						$pos ++;
					}
					for( $j = 0; $j < $pos; $j ++ ){
						$result .= $indentStr;
					}
				}
				$prevChar = $char;
			}

			return $result;
		}


		/**
		 * Return TRUE, if haystack string is REGEX
		 * @param string $haystackString
		 * @return bool
		 */
		static function is_regex( $haystackString ){ return preg_match( "/^\/[\s\S]+\/$/", $haystackString ) > 0; }


		/**
		 * Return TRUE, if haystack string is JSON
		 * @param string $haystack
		 * @param bool   $returnIfFalse
		 * @param bool   $returnDecodeIfJson
		 * @return bool|mixed
		 */
		static function is_json( $haystack, $returnIfFalse = false, $returnDecodeIfJson = true ){
			if( !is_string( $haystack ) || empty( $haystack ) ){
				return $returnIfFalse;
			}
			$decode = json_decode( $haystack, true );

			return ( json_last_error() == JSON_ERROR_NONE ) ? ( $returnDecodeIfJson ? $decode : true ) : $returnIfFalse;
		}


		/**
		 * Return TRUE, if haystack variable is empty. Functions convertvariable to string
		 * @param      $haystack
		 * @param bool $default
		 * @return bool
		 */
		static function is_empty( $haystack, $default = true ){
			return ( !is_array( $haystack ) && ( is_null( $haystack ) || $haystack === false || trim( (string)$haystack ) == '' ) ) ? $default : false;
		}


		/**
		 * @param        $text
		 * @param        $limit
		 * @param string $ellipsis
		 * @return string
		 */
		static function truncate_by_chars( $text, $limit, $ellipsis = '...' ){
			if( strlen( $text ) > $limit ){
				$endpos = strpos( str_replace( [ "\r\n", "\r", "\n", "\t" ], ' ', $text ), ' ', $limit );
				if( $endpos !== false ) $text = trim( mb_substr( $text, 0, $endpos ) ) . $ellipsis;
			}
			return $text;
		}


		/**
		 * @param        $text
		 * @param        $limit
		 * @param string $ellipsis
		 * @return string
		 */
		static function truncate_by_words( $text, $limit, $ellipsis = '...' ){
			$words = preg_split( "/[\n\r\t ]+/", $text, $limit + 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE );
			if( count( $words ) > $limit ){
				end( $words ); //ignore last element since it contains the rest of the string
				$last_word = prev( $words );

				$text = mb_substr( $text, 0, $last_word[1] + strlen( $last_word[0] ) ) . $ellipsis;
			}
			return $text;
		}


		/**
		 * base::convert_str_toId()
		 * @param mixed   $string
		 * @param string  $default_unknown_symbol
		 * @param integer $limit
		 * @param bool    $useRegistr
		 * @param bool    $ifEmpty_generateRandomKey
		 * @param array   $additionSymbolsArr
		 * @return bool|int|string
		 */
		static function sanitize_id( $string, $default_unknown_symbol = '_', $limit = 99, $useRegistr = false, $ifEmpty_generateRandomKey = true, $additionSymbolsArr = [] ){
			$symbolsAllowArr = [
				'а' => 'a',
				'б' => 'b',
				'в' => 'v',
				'г' => 'g',
				'д' => 'd',
				'е' => 'e',
				'ё' => 'e',
				'ж' => 'zh',
				'з' => 'z',
				'и' => 'i',
				'й' => 'y',
				'к' => 'k',
				'л' => 'l',
				'м' => 'm',
				'н' => 'n',
				'о' => 'o',
				'п' => 'p',
				'р' => 'r',
				'с' => 's',
				'т' => 't',
				'у' => 'u',
				'ф' => 'f',
				'х' => 'h',
				'ц' => 'c',
				'ч' => 'ch',
				'ш' => 'sh',
				'щ' => 'sh',
				'ъ' => '',
				'ы' => 'i',
				'ь' => '',
				'э' => 'e',
				'ю' => 'yu',
				'я' => 'ya',

				'А' => 'a',
				'Б' => 'b',
				'В' => 'v',
				'Г' => 'g',
				'Д' => 'd',
				'Е' => 'e',
				'Ё' => 'e',
				'Ж' => 'zh',
				'З' => 'z',
				'И' => 'i',
				'Й' => 'y',
				'К' => 'k',
				'Л' => 'l',
				'М' => 'm',
				'Н' => 'n',
				'О' => 'o',
				'П' => 'p',
				'Р' => 'r',
				'С' => 's',
				'Т' => 't',
				'У' => 'u',
				'Ф' => 'f',
				'Х' => 'h',
				'Ц' => 'c',
				'Ч' => 'ch',
				'Ш' => 'sh',
				'Щ' => 'sh',
				'Ъ' => '',
				'Ы' => 'i',
				'Ь' => '',
				'Э' => 'e',
				'Ю' => 'yu',
				'Я' => 'ya',

				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',

				'a' => 'a',
				'b' => 'b',
				'c' => 'c',
				'd' => 'd',
				'e' => 'e',
				'f' => 'f',
				'g' => 'g',
				'h' => 'h',
				'i' => 'i',
				'j' => 'j',
				'k' => 'k',
				'l' => 'l',
				'm' => 'm',
				'n' => 'n',
				'o' => 'o',
				'p' => 'p',
				'q' => 'q',
				'r' => 'r',
				's' => 's',
				't' => 't',
				'u' => 'u',
				'v' => 'v',
				'w' => 'w',
				'x' => 'x',
				'y' => 'y',
				'z' => 'z',
				' ' => '-',
				'_' => '_',
				'-' => '-',
				'(' => '-',
				')' => '-',
				'&' => '-',
				'~' => '-',
				'[' => '-',
				']' => '-',
				'%20' => '-',
				'+' => '-',
				'=' => '-',
				',' => '-',
				'.' => '-'
			];
			///
			if( !is_array( $additionSymbolsArr ) || count( $additionSymbolsArr ) == 0 ){
				$additionSymbolsArr = [];
			} else {
				$symbolsAllowArr = array_merge( $symbolsAllowArr, $additionSymbolsArr );
			}
			///
			if( !is_string( $string ) && !is_int( $string ) ){
				return $ifEmpty_generateRandomKey ? self::rand() : '';
			}
			$R = '';
			if( is_int( $string ) ){
				return strlen( $string ) > $limit ? substr( $string . '', 0, $limit ) : $string;
			} else {
				for( $list_n = 0; $list_n < strlen( $string ) and $list_n < $limit; $list_n ++ ){
					$symStr = mb_substr( $string, $list_n, 1 ) . '';
					$symStrLow = mb_strtolower( $symStr );
					if( in_array( ord( $symStr ), [ 208, 209 ] ) ){
						//$symStr = (string)substr( $in_name, $list_n, 2 );
						//$symStrLow = (string)mb_strtolower( $symStr, 'UTF-8' );
						//$list_n ++;
					} //Если киррилица, брать 2 символа
					///
					$convertStr = $default_unknown_symbol;
					if( isset( $symbolsAllowArr[ $symStr ] ) ){
						$convertStr = $symbolsAllowArr[ $symStr ];
					} else if( !$useRegistr && isset( $symbolsAllowArr[ $symStrLow ] ) ){
						$convertStr = $symbolsAllowArr[ $symStrLow ];
					} else if( $useRegistr && isset( $symbolsAllowArr[ $symStrLow ] ) ){
						$convertStr = strtoupper( $symbolsAllowArr[ $symStrLow ] );
					}
					///
					$R .= $convertStr;
				}
			}
			////
			return rtrim( strtr( $R, [ '___' => '-', '__' => '-' ] ), '-_ ' );
		}


		static function sanitize_phone( $phoneNumber ){
			$phoneNumber = preg_replace( '/[^0-9]/', '', $phoneNumber );

			if( strlen( $phoneNumber ) > 10 ){
				$countryCode = substr( $phoneNumber, 0, strlen( $phoneNumber ) - 10 );
				$areaCode = substr( $phoneNumber, - 10, 3 );
				$nextThree = substr( $phoneNumber, - 7, 3 );
				$lastFour = substr( $phoneNumber, - 4, 4 );

				$phoneNumber = '+' . $countryCode . '(' . $areaCode . ')' . $nextThree . '-' . $lastFour;
			} else if( strlen( $phoneNumber ) == 10 ){
				$areaCode = substr( $phoneNumber, 0, 3 );
				$nextThree = substr( $phoneNumber, 3, 3 );
				$lastFour = substr( $phoneNumber, 6, 4 );

				$phoneNumber = '(' . $areaCode . ')' . $nextThree . '-' . $lastFour;
			} else if( strlen( $phoneNumber ) == 7 ){
				$nextThree = substr( $phoneNumber, 0, 3 );
				$lastFour = substr( $phoneNumber, 3, 4 );

				$phoneNumber = $nextThree . '-' . $lastFour;
			}

			return $phoneNumber;
		}

	}