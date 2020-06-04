<?php

	namespace theme\_minify;


	use hiweb\components\CSS_Parser\CSS_Parser;
	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;
	use WP_Styles;


	class css extends scripts{


		private $file_critical_append;


		public function __construct( $template ){
			parent::__construct( $template );
			$this->file_full_append = 'full.css';
			$this->file_critical_append = 'critical.css';
		}


		/**
		 * @param $wp_styles
		 * @return bool|-1
		 */
		public function try_generate_full_file_from_wp_styles( WP_Styles $wp_styles ){
			$files = [];
			$R = [];
			foreach( $wp_styles->done as $handle ){
				if( !isset( $wp_styles->registered[ $handle ] ) ) continue;
				$file = PathsFactory::get_file($wp_styles->registered[ $handle ]->src );
				if( $file->is_readable() && $file->is_file() && $file->Path()->is_local() ){
					$R[ $file->get_path_relative() ] = [ 'size' => $file->get_size(), 'filemtime' => filemtime( $file->get_path() ) ];
					$files[] = $file;
				}
			}
			$md5sum = md5( json_encode( $R ) );
			///check md5sum
			if( $this->is_exists() && $this->get_file_hash() == $md5sum && $this->is_alive() ){
				return - 1;
			} else {
				//update full content
				$css = '';
				$imports = [];
				$font_faces = [];
				foreach( $files as $file ){
					$css_parse = css_parser::get( $file );
					$imports = array_merge( $imports, $css_parse->get_imports() );
					$font_faces = array_merge( $font_faces, $css_parse->get_font_faces() );
					$css .= $css_parse->get_content( false, false );
				}
				$font_faces = array_unique( $font_faces );
				$R = ( trim( $css ) == '' && count( $imports ) == 0 && count( $font_faces ) == 0 ) ? false : join( '', $imports ) . join( '', $font_faces ) . $css;
				return $this->cache()->do_update( $R, $this->file_full_append, $md5sum );
			}
		}

		/**
		 * @return string
		 */
		private function get_critical_file_path(){
			return $this->_template()->cache()->get_cache_path( $this->file_critical_append );
		}
		
		
		/**
		 * @return \hiweb\core\Paths\Path
		 */
		public function get_critical_file(){
			return PathsFactory::get( $this->get_critical_file_path() );
		}


		/**
		 * @return bool|string
		 */
		public function get_critical_content(){
			return $this->get_critical_file() instanceof Path ? $this->get_critical_file()->file()->get_content( false ) : false;
		}


		/**
		 * @return bool|null
		 */
		public function is_critical_exists(){
			return $this->get_critical_file()->file()->is_exists();
		}


		/**
		 * @return bool
		 */
		public function is_critical_must_update(){
			return !( isset( $this->cache()->data[ $this->file_critical_append . '-hash' ] ) && isset( $this->cache()->data[ $this->data_scripts_key . '-hash' ] ) && $this->cache()->data[ $this->file_critical_append . '-hash' ] == $this->cache()->data[ $this->data_scripts_key . '-hash' ] );
		}


		/**
		 * @param null $cHtml - null - if cHtml file exists!
		 * @param null $url_referer_for_pages_cache_update
		 * @return bool|int
		 */
		public function try_generate_critical_css( $cHtml = null, $url_referer_for_pages_cache_update = null ){
			if( !is_string( $cHtml ) && $this->_template()->html()->is_critical_exists() ){
				$cHtml = $this->_template()->html()->get_critical_file()->file()->get_content();
			}
			if( !is_string( $cHtml ) || trim( $cHtml ) == '' ) return - 1;
			if( !$this->is_exists() ) return - 2;
			$css_parse = css_parser::get_from_string( $this->get_content() );
			$cCss = $css_parse->get_content_filtered_by_html( $this->_template()->html()->get_critical_content(), false, false, true );
			////
			$B = $this->cache()->do_update( $cCss, $this->file_critical_append );
			$this->cache()->data[ $this->file_critical_append . '-hash' ] = $this->cache()->data[ $this->data_scripts_key . '-hash' ];
			return $B;
		}

	}