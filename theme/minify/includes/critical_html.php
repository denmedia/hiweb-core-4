<?php

	namespace theme\_minify;


	class critical_html extends scripts{

		public function __construct( $template ){
			parent::__construct( $template );
			$this->file_full_append = 'critical.html';
		}


		/**
		 * @param $cHtml
		 * @return bool
		 */
		public function try_generate( $cHtml ){
			if($this->is_alive()) return -1;
			return $this->cache()->do_update( $cHtml, $this->file_full_append );
		}

	}