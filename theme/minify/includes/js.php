<?php

	namespace theme\_minify;


	use Exception;
	use hiweb\core\Paths\PathsFactory;
	use hiweb\vendors\JShrink\Minifier;
	use WP_Scripts;


	class js extends scripts{

		public function __construct( $template ){
			parent::__construct( $template );
			$this->file_full_append = 'full.js';
			$this->data_scripts_key = 'scripts';
		}


		/**
		 * @param $wp_scripts
		 * @return bool|-1
		 * @throws Exception
		 */
		public function try_generate_full_file_from_wp_scripts( WP_Scripts $wp_scripts ){
			$files = [];
			$R = [];
			foreach( $wp_scripts->done as $handle ){
				if( !isset( $wp_scripts->registered[ $handle ] ) ) continue;
				$file = PathsFactory::get_file( $wp_scripts->registered[ $handle ]->src );
				if($file->Path()->is_local() && $file->is_readable() && $file->is_file() ){
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
				$full_content = [];
				foreach( $files as $file ){
					$full_content[] = $file->get_content();
				}
				$R = join( "\n", $full_content );
				$full_content_test = Minifier::minify( $R, [ 'flaggedComments' => false ] );
				if( is_string( $full_content_test ) && strlen( $full_content_test ) > 0 ){
					$R = $full_content_test;
				}
				return $this->cache()->do_update( $R, $this->file_full_append, $md5sum );
			}
		}


	}