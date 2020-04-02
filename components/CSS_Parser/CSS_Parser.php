<?php

	namespace hiweb\components\CSS_Parser;


	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;
	use hiweb\css_parser\media;
	use hiweb\css_parser\rule;


	class CSS_Parser{

		/** @var CSS_Parser[] */
		static $files = [];
		/** @var CSS_Parser[] */
		static $strings = [];
		static $prepare_patterns = [
			'charset' => '@charset\s+[\'"][\w\d-_]+[\'"]\s*;',
			'ms_viewport' => '@-ms-viewport\s*{\s*[^}]+\s*}',
			'comment' => '\/\*[^*]*\*+(?>[^\/][^*]*\*+)*\/',
			'import' => '@import\s+[^;]+;',
			'media' => '(?>@media|@supports)\s+[^{]+{\s*(?>[a-z0-9\s\.\:#_\-@,>=\[\"\'\^\+~\]\*\(\)]+\{[^\}]+\}[\s]*)+\}?',
			'font_face' => '@font-face\s*{[^}]+}',
			'keyframes' => '\@(?>-webkit-|-moz-|-o-)?keyframes\s+[\w-_]+\s*\{(?>\s*[\w\d%]+\s*\{[^}]+\s*\}\s*)+\}',
			'selector' => '[a-z0-9\s\.\:#_\-,>=\[\"\'\^\+~\]\*\(\)]+\{[^\}]+\}\s*\}?'
		];
		static $prepare_pattern = '/(?:(?<=}|;|\*\/|\n|^|\s)(%PATTERN%))/im';


		/**
		 * @param $filePath_or_URL
		 * @return CSS_Parser
		 */
		static function get( $filePath_or_URL ){
			if( !$filePath_or_URL instanceof Path ){
				$path = PathsFactory::get( $filePath_or_URL );
			} else $path = $filePath_or_URL;

			if( $path->is_local() ){
				$file_key = $path->get_path_relative();
			} else {
				$file_key = $path->Url()->get();
			}
			if( !isset( self::$files[ $file_key ] ) ){
				self::$files[ $file_key ] = new CSS_Parser( $path );
			}
			return self::$files[ $file_key ];
		}


		/**
		 * @param string $css_string
		 * @return CSS_Parser
		 */
		static function get_from_string( $css_string = 'body { font-size: 1rem; }' ){
			$string_key = md5( $css_string );
			if( !isset( self::$strings[ $string_key ] ) ){
				self::$strings[ $string_key ] = new CSS_Parser( $css_string );
			}
			return self::$strings[ $string_key ];
		}


		/**
		 * @return string
		 */
		static private function get_prepare_pattern(){
			$R = [];
			foreach( self::$prepare_patterns as $key => $pattern ){
				$R[] = "(?<{$key}>{$pattern})";
			}
			return str_replace( '%PATTERN%', join( '|', $R ), self::$prepare_pattern );
		}


		////ITEM


		/** @var path */
		private $path_object;
		private $content;
		private $content_parsed;
		private $imports = [];
		private $font_faces = [];
		private $keyframes = [];
		protected $set_relative_urls = true;


		/**
		 * css_parser constructor.
		 * @param path|string $filePath_or_cssContent
		 * @param bool        $set_relative_urls
		 */
		public function __construct( $filePath_or_cssContent, $set_relative_urls = true ){
			if( $filePath_or_cssContent instanceof path ){
				$this->path_object = $filePath_or_cssContent;
			} else {
				$this->content = $filePath_or_cssContent;
			}
			$this->set_relative_urls = $set_relative_urls;
			$this->prepare_content();
		}


		private function filter_css_string( $css_string, $exclude_comments = true ){
			if( $exclude_comments ){
				$css_string = preg_replace( '/' . self::$prepare_patterns['comment'] . '/im', '', $css_string );
			}
			if( $this->set_relative_urls && $this->path_object instanceof path ){
				///URL REPLACE
				preg_match_all( '/url\(\s?[\'"]?(?<url>(?!data:|http[s]?:\/\/)[^)\'"]+)[\'"]?\s?\)/im', $css_string, $matches );
				$strtr = [];
				foreach( $matches['url'] as $url ){
					$result = preg_match( '/^(\.\.\/)+/i', trim( $url, '"\'' ) );
					$url_dest = $this->path_object->File()->dirname();
					for( $n = 0; $n < $result; $n ++ ){
						$url_dest = dirname( $url_dest );
					}
					$url_dest = 'url(' . PathsFactory::get( $url_dest )->Url()->get() . '/' . preg_replace( '/^(\.\.\/)+/i', '', $url ) . ')';
					$url = "url({$url})";
					$strtr[ $url ] = $url_dest;
				}
				$css_string = strtr( $css_string, $strtr );
			}
			return $css_string;
		}


		/**
		 *
		 */
		private function prepare_content(){
			if( preg_match_all( self::get_prepare_pattern(), $this->filter_css_string( $this->get_content_source() ), $matches ) > 0 ){
				foreach( $matches[0] as $index => $match ){
					foreach( self::$prepare_patterns as $type => $pattern ){
						$content = trim( $matches[ $type ][ $index ] );
						$content = php_html_css_js_minifier::minify_css( $content );
						if( $content != '' ){
							$this->content_parsed[ $index ] = [
								'type' => $type,
								'content' => $content,
								'object' => null
							];
							switch( $type ){
								case 'import':
									$this->imports[] = $content;
									break;
								case 'font_face':
									$this->font_faces[] = $content;
									break;
								case 'keyframes':
									$this->keyframes[] = $content;
									break;
								case 'media':
									$this->content_parsed[ $index ]['object'] = new media( $this, $content );
									break;
								case 'selector':
									$this->content_parsed[ $index ]['object'] = new rule( $this, $content );
									break;
							}
							break;
						}
					}
				}
			}
			$this->imports = array_unique( $this->imports );
			$this->font_faces = array_unique( $this->font_faces );
			$this->keyframes = array_unique( $this->keyframes );
		}


		/**
		 * @return string[]
		 */
		public function get_imports(){
			return $this->imports;
		}


		/**
		 * @return string[]
		 */
		public function get_font_faces(){
			return $this->font_faces;
		}


		/**
		 * @return string[]
		 */
		public function get_keyframes(){
			return $this->keyframes;
		}


		/**
		 * @return array
		 */
		public function get_content_parsed(){
			return $this->content_parsed;
		}


		/**
		 * @return string
		 */
		public function get_content_source(){
			if( $this->path_object instanceof path ){
				$R = $this->path_object->get_content( '' );
			} else {
				$R = $this->content;
			}

			return $R;
		}


		/**
		 * Возвращает сжатый CSS
		 * @param bool $include_imports
		 * @param bool $include_font_faces
		 * @param bool $include_keyframes
		 * @return string
		 */
		public function get_content( $include_imports = true, $include_font_faces = true, $include_keyframes = true ){
			$R = [];
			if( $include_imports ) $R = array_merge( $R, join( '', $this->imports ) );
			if( $include_font_faces ) $R = array_merge( $R, join( '', $this->font_faces ) );
			if( $include_keyframes ) $R = array_merge( $R, join( '', $this->keyframes ) );
			foreach( $this->get_content_parsed() as $rule ){
				if( $rule['object'] instanceof media ){
					$R[] = $rule['object']->get_css_media_content( false );
				} elseif( $rule['object'] instanceof rule ) {
					$R[] = $rule['object']->get_css_content();
				} elseif( $rule['type'] != 'import' && $rule['type'] != 'font_face' && $rule['type'] != 'keyframes' ) {
					$R[] = $rule['content'];
				}
			}
			$R = array_unique( $R );
			return join( "", $R );
		}


		/**
		 * @param      $html
		 * @param bool $include_imports
		 * @param bool $include_font_faces
		 * @param bool $include_keyframes
		 * @param bool $include_medias
		 * @return string
		 */
		public function get_content_filtered_by_html( $html, $include_imports = true, $include_font_faces = true, $include_keyframes = true, $include_medias = true ){
			require_once HIWEB_DIR_VENDORS . '/phpQuery.php';
			\phpQuery::newDocumentHTML( $html );
			$R = [];
			if( $include_imports ) $R = array_merge( $R, $this->get_imports() );
			if( $include_font_faces ) $R = array_merge( $R, $this->get_font_faces() );
			if( $include_keyframes ) $R = array_merge( $R, $this->get_keyframes() );
			foreach( $this->get_content_parsed() as $rule_or_media ){
				if( $rule_or_media['object'] instanceof media || $rule_or_media['object'] instanceof rule ){
					$rule_or_media = $rule_or_media['object'];
					foreach( $rule_or_media->get_selectors() as $selector ){
						$dots_pos = strpos( $selector, ':' );
						if( $dots_pos !== false ) $selector = substr( $selector, 0, $dots_pos );
						if( pq( $selector )->length > 0 ){
							if( $rule_or_media instanceof media && $include_medias ){
								$R[] = $rule_or_media->get_css_media_content( false );
							} elseif( $rule_or_media instanceof rule ) {
								$R[] = $rule_or_media->get_css_content();
							} else
								break;
						}
					}
				}
			}
			$R = array_unique( $R );
			return join( '', $R );
		}


	}