<?php

	namespace theme\_minify;


	use hiweb\core\Paths\PathsFactory;


	class html extends scripts{

		private $file_critical_append = 'critical.html';


		public function __construct( $template ){
			parent::__construct( $template );
			$this->file_full_append = 'full.html';
			$this->data_scripts_key = 'pages';
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
		 * @return bool|null
		 */
		public function is_critical_exists(){
			return $this->get_critical_file()->file()->is_exists();
		}


		/**
		 * @return string|bool
		 */
		public function get_critical_content(){
			return $this->get_critical_file()->file()->get_content( false );
		}


		/**
		 * @param $fullHtml
		 * @return bool
		 */
		public function do_update_full( $fullHtml ){
			return $this->_template()->cache()->do_update( $fullHtml, $this->file_full_append );
		}


		/**
		 * @param $cHtml
		 * @return bool
		 */
		public function do_update_critical( $cHtml ){
			$B = $this->cache()->do_update( $cHtml, $this->file_critical_append );
			return $B;
		}

	}