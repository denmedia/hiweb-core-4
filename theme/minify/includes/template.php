<?php

	namespace theme\_minify;


	use hiweb\paths;
	use hiweb\paths\path;


	class template{

		static $templates = [];
		static $current_template_path;


		static function get( $template_path = null ){
			if( !is_string( $template_path ) ) $template_path = self::$current_template_path;
			///check by cache id
			if( strpos( $template_path, '/' ) === false ){
				$tmp = cache::get_template_path_by_id( $template_path );
				if( $tmp !== false ) $template_path = $tmp;
			}
			//
			$template_path = paths::get( $template_path )->get_path_relative();
			if( !isset( self::$templates[ $template_path ] ) ){
				self::$templates[ $template_path ] = new template( $template_path );
			}
			return self::$templates[ $template_path ];
		}


		///ITEM
		private $path;
		/** @var js */
		private $js;
		/** @var css */
		private $css;
		/** @var html */
		private $html;
		/** @var critical_html */
		private $critical_html;
		/** @var critical_css */
		private $critical_css;
		/** @var cache */
		private $cache;


		public function __construct( $template_path ){
			$this->path = $template_path;
		}


		/**
		 * @return cache
		 */
		public function cache(){
			if( !$this->cache instanceof cache ){
				$this->cache = new cache( $this );
			}
			return $this->cache;
		}


		/**
		 * @return js
		 */
		public function js(){
			if( !$this->js instanceof js ){
				$this->js = new js( $this );
			}
			return $this->js;
		}


		/**
		 * @return css
		 */
		public function css(){
			if( !$this->css instanceof css ){
				$this->css = new css( $this );
			}
			return $this->css;
		}


		/**
		 * @return html
		 */
		public function html(){
			if( !$this->html instanceof html ){
				$this->html = new html( $this );
			}
			return $this->html;
		}


		/**
		 * @return critical_html
		 */
		public function critical_html(){
			if( !$this->critical_html instanceof critical_html ){
				$this->critical_html = new critical_html( $this );
			}
			return $this->critical_html;
		}


		/**
		 * @return critical_css
		 */
		public function critical_css(){
			if( !$this->critical_css instanceof critical_css ){
				$this->critical_css = new critical_css( $this );
			}
			return $this->critical_css;
		}


		/**
		 * @return string
		 */
		public function get_path(){
			return $this->path;
		}

	}