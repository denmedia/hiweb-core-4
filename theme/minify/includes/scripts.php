<?php

	namespace theme\_minify;


	use hiweb\paths;
	use hiweb\paths\path;
	use theme\minify;


	class scripts{

		/** @var template */
		private $template;
		protected $file_full_append = 'full.txt';
		protected $data_scripts_key = 'files';
		protected $data_files;
		protected $files;


		public function __construct( $template ){
			$this->template = $template;
		}


		/**
		 * @return template
		 */
		public function _template(){
			return $this->template;
		}


		/**
		 * @return cache
		 */
		public function cache(){
			return $this->_template()->cache();
		}


		/**
		 * @return array
		 */
		//		public function get_cache_files_data(){
		//			if( is_array( $this->cache()->data[ $this->file_full_append ] ) ){
		//				return $this->cache()->data[ $this->file_full_append ];
		//			}
		//			return [];
		//		}

		/**
		 * Return MD5 file hash
		 * @return bool|mixed
		 */
		public function get_file_hash(){
			$data = $this->cache()->get_file_data( $this->file_full_append );
			return array_key_exists( 'md5sum', $data ) ? $data['md5sum'] : false;
		}


		/**
		 * @return bool
		 */
		public function is_alive(){
			return intval($this->cache()->get_file_data( $this->file_full_append )['time']) != 0 && (time() - $this->cache()->get_file_data( $this->file_full_append )['time'] < minify::$cache_refresh_time);
		}


		/**
		 * @return false|string
		 */
		public function get_content(){
			return file_get_contents( $this->_template()->cache()->get_cache_path( $this->file_full_append ) );
		}


		/**
		 * @return path[]
		 */
		//		public function get_files(){
		//			if( !is_array( $this->files ) ){
		//				$this->files = [];
		//				foreach( $this->get_cache_files_data() as $path => $file_data ){
		//					$file = paths::get( $path );
		//					$this->files[ $file->get_path_relative() ] = $file;
		//				}
		//			}
		//			return $this->files;
		//		}

		/**
		 * @return array
		 */
		//		public function get_files_data(){
		//			if( !is_array( $this->data_files ) ){
		//				$this->data_files = [];
		//				foreach( $this->get_files() as $relative_path => $file ){
		//					$this->data_files[ $file->get_path_relative() ] = [ 'size' => $file->get_size(), 'filemtime' => filemtime( $file->get_path() ) ];
		//				}
		//			}
		//			return $this->data_files;
		//		}

		/**
		 * @param $file_paths
		 * @return bool
		 * @deprecated
		 */
		//		public function set_files( $file_paths ){
		//			$R = [];
		//			if( is_array( $file_paths ) ){
		//				foreach( $file_paths as $path ){
		//					$file = paths::get( $path );
		//					if( $file->is_local() && $file->is_readable() ){
		//						$R[ $file->get_path_relative() ] = [ 'size' => $file->get_size(), 'filemtime' => filemtime( $file->get_path() ) ];
		//					}
		//				}
		//			}
		//			if( !isset( $this->cache()->data[ $this->data_scripts_key . '-hash' ] ) || $this->cache()->data[ $this->data_scripts_key . '-hash' ] != md5( json_encode( $R ) ) ){
		//				$this->cache()->data[ $this->data_scripts_key ] = $R;
		//				$this->cache()->data[ $this->data_scripts_key . '-hash' ] = md5( json_encode( $R ) );
		//				return $this->cache()->do_update( $this->cache()->data );
		//			}
		//			return false;
		//		}

		/**
		 * @return string|bool
		 * @deprecated
		 * Get full content from files
		 */
		//		protected function generate_full_content(){
		//			$R = '';
		//			foreach( $this->get_files() as $relative_path => $file ){
		//				$content = $file->get_content();
		//				if( trim( $content ) == '' ) continue;
		//				$R .= $content . "\n\n";
		//			}
		//			return $R == '' ? false : $R;
		//		}

		/**
		 * @return bool|int
		 * @deprecated
		 * Generate full content file
		 */
		//		public function do_make_file_full(){
		//			if( is_array( $this->cache()->data[ $this->data_scripts_key ] ) ){
		//				$cache_data = json_encode( $this->get_files_data() );
		//				$current_data = json_encode( $this->get_files_data() );
		//				if( !$this->cache()->is_exists( $this->file_full_append ) || $cache_data != $current_data ){
		//					return $this->cache()->do_update( $this->generate_full_content(), $this->file_full_append );
		//				}
		//				return 0;
		//			}
		//			return false;
		//		}

		/**
		 * @return string
		 */
		public function get_full_path(){
			return $this->cache()->get_cache_path( $this->file_full_append );
		}


		/**
		 * @return bool
		 */
		public function is_exists(){
			return file_exists( $this->get_full_path() ) && is_file( $this->get_full_path() );
		}


		/**
		 * @return bool|string
		 */
		public function get_full_url(){
			if( !$this->is_exists() ) return false;
			return paths::get( $this->cache()->get_cache_path( $this->file_full_append ) )->get_url();
		}


		/**
		 * @return bool
		 */
		//		public function is_must_update(){
		//			return false;
		//		}

	}