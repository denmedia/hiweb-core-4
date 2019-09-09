<?php

	namespace hiweb\css_parser;


	use hiweb\css_parser;


	class media{

		/** @var css_parser */
		private $css_parser;
		private $prepare_pattern = '/(?<media>(@media|@supports)\s[^{]+)\s*\{\s*(?<content>[.\s\S]+)\s*\}\s*$/im';
		/** @var string */
		private $css_media_content;
		public $media;
		private $content;
		private $content_explode;
		private $rules;
		private $selectors;


		/**
		 * selector constructor.
		 * @param css_parser $css_parser
		 * @param            $css_media_content
		 */
		public function __construct( css_parser $css_parser, $css_media_content ){
			$this->css_parser = $css_parser;
			$this->css_media_content = $css_media_content;
			$this->prepare();
			$this->get_selectors();
		}


		/**
		 * @param bool $return_original
		 * @return string
		 */
		public function get_css_media_content( $return_original = false ){
			if( $return_original ) return $this->css_media_content;
			$R = "{$this->get_media()}{";
			foreach( $this->get_rules() as $rule ){
				if( !$rule->is_exists() ) continue;
				$R .= $rule->get_css_content( false );
			}
			$R .= '}';
			return $R;
		}


		/**
		 * @param $html
		 * @return string
		 */
		public function get_css_media_content_filtered_by_html( $html ){
			require_once HIWEB_DIR_VENDORS.'/phpQuery.php';
			$R = [];
			\phpQuery::newDocumentHTML( $html );
			foreach( $this->get_rules() as $rule ){
				foreach( $rule->get_selectors() as $selector ){
					if( pq( $selector )->length > 0 ){
						$R[] = $rule->get_css_content();
					}
				}
			}
			return join( '', $R );
		}


		/**
		 *
		 */
		private function prepare(){
			preg_match_all( $this->prepare_pattern, $this->get_css_media_content( true ), $matches );
			if( isset( $matches['media'] ) ){
				$this->media = trim( reset( $matches['media'] ) );
			}
			if( isset( $matches['content'] ) ){
				$this->content = trim( reset( $matches['content'] ) );
				foreach( explode( '}', $this->content ) as $rule ){
					$rule = trim( $rule );
					if( $rule == '' ) continue;
					$this->content_explode[] = $rule . '}';
				}
			}
		}


		/**
		 * @return bool
		 */
		public function is_exists(){
			return ( $this->media != '' && count( $this->content_explode ) > 0 );
		}


		/**
		 * @return mixed
		 */
		public function get_media(){
			return $this->media;
		}


		/**
		 * @return string
		 */
		public function get_content(){
			return $this->css_media_content;
		}


		/**
		 * @return rule[]
		 */
		public function get_rules(){
			if( !is_array( $this->rules ) ){
				$this->rules = [];
				foreach( $this->content_explode as $rule_content ){
					$this->rules[] = new rule( $this->css_parser, $rule_content );
				}
			}
			return $this->rules;
		}


		/**
		 * @return array
		 */
		public function get_selectors(){
			if( !is_array( $this->selectors ) ){
				$this->selectors = [];
				foreach( $this->get_rules() as $rule ){
					$this->selectors = array_merge( $this->selectors, $rule->get_selectors( false ) );
				}
			}
			return $this->selectors;
		}

	}