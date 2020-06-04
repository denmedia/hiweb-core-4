<?php
	
	namespace theme\_minify;
	
	
	class critical_css extends scripts{
		
		public function __construct( $template ){
			parent::__construct( $template );
			$this->file_full_append = 'critical.css';
		}
		
		
		/**
		 * @return bool|int
		 */
		public function try_generate(){
			if( !$this->_template()->css()->is_exists() ) return - 1;
			if( !$this->_template()->critical_html()->is_exists() ) return - 2;
			$md5sum = md5( $this->_template()->css()->get_file_hash() . $this->_template()->critical_html()->get_file_hash() );
			if( $md5sum == $this->get_file_hash() ) return - 3;
			///
			$css_parser = \hiweb\components\CSS_Parser\CSS_Parser::get_from_string( $this->_template()->css()->get_content() );
			$critical_css = $css_parser->get_content_filtered_by_html( $this->_template()->critical_html()->get_content(), false, false );
			///
			return $this->cache()->do_update( $critical_css, $this->file_full_append, $md5sum );
		}
		
		
	}