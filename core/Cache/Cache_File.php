<?php

	namespace hiweb\core\Cache;


	use hiweb\components\Console\ConsoleFactory;
	use hiweb\core\Paths\Path_File;
	use hiweb\core\Paths\PathsFactory;
	use hiweb\core\Strings;


	class Cache_File{

		//private $dir = WP_CONTENT_DIR . '/cache/hiweb';

		/**
		 * @var Cache
		 */
		private $Cache;
		private $Path;
		private $lifetime_seconds = 0;
		private $enable = false;
		private $file_extension = 'json';

		static $option_default_life_time_seconds = 86400;


		public function __construct( Cache $Cache, $fileName = null ){
			$this->Cache = $Cache;
			$this->lifetime_seconds = self::$option_default_life_time_seconds;
			if( !is_string( $fileName ) || trim( $fileName ) == '' ){
				$fileName = convert_name_group_to_fileName( $Cache->get_variable_name(), $Cache->get_group_name() );
			} else {
				$fileName = Strings::sanitize_id( $fileName, '_', 48 );
			}
			$this->Path = PathsFactory::get( CacheFactory::$cache_dir . '/' . $fileName . '.' . $this->file_extension );
		}


		/**
		 * Enable file cache (to write or read cache data from file)
		 * @return $this
		 */
		public function enable(){
			if( !$this->enable ){
				if( !file_exists( CacheFactory::$cache_dir ) ) mkdir( CacheFactory::$cache_dir, 0755, true );
				if( $this->Cache()->is_set() ){
					$this->set( $this->Cache->get_value() );
				}
				$this->enable = true;
			}
			return $this;
		}


		/**
		 * @return bool
		 */
		public function is_enable(){
			return $this->enable;
		}


		/**
		 * @return Cache
		 */
		public function Cache(){
			return $this->Cache;
		}


		/**
		 * @return Path_File
		 */
		public function File(){
			return $this->Path->File();
		}


		/**
		 * Read and return value from file
		 * @return mixed|null
		 */
		public function get(){
			$R = null;
			if( $this->File()->is_readable() ){
				$file_data = json_decode( $this->File()->get_content(), true );
				if( json_last_error() == JSON_ERROR_NONE ){
					$R = $file_data;
				} else {
					ConsoleFactory::add( 'Error while read cache file [' . $this->File()->get_path_relative() . ']', 'warn', __CLASS__, [], true );
				}
			} else {
				ConsoleFactory::add( 'Can\'t read cache file [' . $this->File()->get_path_relative() . ']', 'warn', __CLASS__, [], true );
			}
			return $R;
		}


		/**
		 * Write current cache value to file
		 * @param mixed $value
		 * @param bool  $force
		 * @return bool|int
		 */
		public function set( $value, $force = false ){
			if( $this->is_alive() && !$force ){
				//файл еще актуален, и нет неолбходимости его переписывать
				return true;
			} elseif( !$this->File()->is_exists() || $this->File()->is_writable() || $force ) {
				$R = $this->File()->set_content( json_encode( $value ) );
				if( $R === false ){
					ConsoleFactory::add( 'Can\'t write cache file [' . $this->File()->get_path_relative() . ']', 'warn', __CLASS__, [], true );
				}
				return $R;
			} else {
				ConsoleFactory::add( 'Can\'t write cache file [' . $this->File()->get_path_relative() . '], file is not writable', 'warn', __CLASS__, [], true );
			}
			return false;
		}


		/**
		 * Set file cache lifetime in seconds
		 * 3600 - hour, 86400 - day, 604800 - week, 2.628e+6 - month, 31535965 - year
		 * @param int $seconds
		 * @return Cache_File
		 */
		public function set_lifetime( $seconds = 86400 ){
			$this->lifetime_seconds = $seconds;
			return $this;
		}


		/**
		 * Return delta time of cache life between born and die
		 * @return int
		 */
		public function get_lifetime(){
			return intval( $this->lifetime_seconds );
		}


		/**
		 * Return end life timestamp
		 * @return int
		 */
		public function get_lifetime_stamp(){
			if( !$this->File()->is_exists() || !$this->File()->is_file() ) return 0;
			return filemtime( $this->File()->get_path() ) + $this->get_lifetime();
		}


		/**
		 * Return true, if cache is actually (alive)
		 * @return bool
		 */
		public function is_alive(){
			if( !$this->File()->is_exists() || !$this->File()->is_file() ) return false;
			return $this->get_lifetime_stamp() > microtime( true );
		}

	}