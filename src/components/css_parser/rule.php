<?php

	namespace hiweb\css_parser;


	use hiweb\css_parser;


	class rule{

		/** @var css_parser */
		private $css_parser;
		private $prepare_pattern = '/\s*(?<selector>[a-z0-9\s\.\:#_\-@,>=\[\"\'\^\+~\]\*\(\)]+)\s*\{\s*(?<content>[^\}]+)\s*\}\s*/im';
		/** @var string */
		private $css_rule_content;
		public $selectors;
		public $content;


		/**
		 * selector constructor.
		 * @param css_parser $css_parser
		 * @param            $css_media_content
		 */
		public function __construct( css_parser $css_parser, $css_media_content ){
			$this->css_parser = $css_parser;
			$this->css_rule_content = $css_media_content;
			$this->prepare();
		}


		/**
		 * @param bool $return_original
		 * @return string
		 */
		public function get_css_content( $return_original = false ){
			if( $return_original ) return $this->css_rule_content;
			return "{$this->get_selectors(true)}{{$this->get_content()}}";
		}


		/**
		 *
		 */
		private function prepare(){
			preg_match_all( $this->prepare_pattern, $this->get_css_content( true ), $matches );
			if( isset( $matches['selector'] ) ) foreach( explode( ',', trim( reset( $matches['selector'] ) ) ) as $selector ){
				$selector = trim( $selector );
				if( $selector == '' ) continue;
				$this->selectors[] = $selector;
			}
			if( isset( $matches['content'] ) ) $this->content = reset( $matches['content'] );
		}


		/**
		 * @return bool
		 */
		public function is_exists(){
			return ( count( $this->get_selectors() ) > 0 && strlen( trim( $this->content ) ) > 0 );
		}


		/**
		 * @param bool $return_string
		 * @param bool $return_simple
		 * @return array
		 */
		public function get_selectors( $return_string = false, $return_simple = false ){
			$R = $this->selectors;
			return $return_string ? join( ',', $R ) : $R;
		}


		public function get_content(){
			return $this->content;
		}

	}