<?php

	namespace theme\pages_cache;


	require_once __DIR__ . '/options.php';

	use hiweb\cron;
	use hiweb\urls;


	class queue{

		/** @var array */
		static private $urls;
		static private $option_key = 'hiweb_theme-pages_cache-queue';
		/** @var array|null */
		static private $option_raw_data;
		static private $urls_change = false;
		static private $is_init = false;
		static $background_max_limit = 5;


		static function init(){
			if( !self::$is_init ){
				self::$is_init = true;
				self::$option_raw_data = get_option( self::$option_key, null );
				self::$urls = [];
				if( is_array( self::$option_raw_data ) ) foreach( self::$option_raw_data as $priority => $urls ){
					foreach( $urls as $url => $time ){
						self::add_url( $url, $priority, $time );
					}
				}

				add_action( 'init', function(){
					if( !is_array( self::$option_raw_data ) || count( self::$urls ) == 0 || !is_array( reset( self::$urls ) ) ){
						self::set_default_urls();
					}
					self::do_sort_urls();
				} );

				add_action( 'shutdown', '\\theme\\pages_cache\\queue::_update' );
			}
		}


		/**
		 * Выполнить сортировку массива URL
		 */
		static function do_sort_urls(){
			ksort( self::$urls, SORT_NUMERIC );
			foreach( self::$urls as $priority => $urls ){
				asort( $urls, SORT_NUMERIC );
				self::$urls[ $priority ] = $urls;
			}
		}


		/**
		 * Возвращает массив всех URl и их приоритет
		 * @return array
		 */
		static function get_urls(){
			return self::$urls;
		}


		/**
		 * Flush queue urls
		 * @return bool
		 */
		static function flush(){
			return delete_option( self::$option_key );
		}


		/**
		 * Flush queue urls
		 * @alias self::flush()
		 * @return bool
		 */
		static function clear(){
			$B = self::flush();
			self::$is_init = false;
			self::init();
			self::_update();
			return $B;
		}


		/**
		 * Сохранить (обновить) список URL в БД
		 */
		static function _update(){
			self::init();
			self::do_sort_urls();
			$B = false;
			if( self::$urls_change && options::is_enable() ){
				$B = update_option( self::$option_key, self::$urls );
			}
			return $B;
		}


		/**
		 * Возвращает TRUE (или priority ключ), если заданный URL существует в очереди
		 * @param      $url
		 * @param bool $return_priority_key
		 * @return bool|int|string
		 */
		static function is_url_exists( $url, $return_priority_key = false ){
			$url = tools::sanitize_url( $url );
			foreach( self::$urls as $priority => $urls ){
				if( array_key_exists( $url, $urls ) ){
					return $return_priority_key ? $priority : true;
				}
			}
			return false;
		}


		/**
		 * @version 1.1
		 * @param     $url
		 * @param int $priority
		 * @param int $time
		 * @return array|bool
		 */
		static function add_url( $url, $priority = 5, $time = 0 ){
			$time = intval( $time );
			$priority = intval( $priority );
			$url = tools::sanitize_url( $url );
			if( !options::is_allow_url( $url ) ) return false;
			$priority_key = self::is_url_exists( $url, true );
			if( $priority_key !== false ){
				if( $time < 1 && isset(self::$urls[ $priority ][$url]) ) $time = intval( self::$urls[ $priority ][ $url ] );
				unset( self::$urls[ $priority ][ $url ] );
			}
			self::$urls[ $priority ][ $url ] = $time;
			self::$urls_change = true;
			return self::$urls;
		}


		/**
		 * Добавить URL в очередь, если не существует, или поменять приоритет на более высокий
		 * @param     $url
		 * @param int $priority
		 */
		static function add_url_if_not_exists( $url, $priority = 5 ){
			$find_priority = self::is_url_exists( $url, true );
			if( $find_priority === false || $find_priority > $priority ){
				self::add_url( $url, $priority );
			}
		}


		static function set_default_urls(){
			$R = [];
			///GET URLS BU MENU
			$nav_menu_ids = array_values( get_nav_menu_locations() );
			array_unique( $nav_menu_ids );
			foreach( $nav_menu_ids as $nav_menu_id ){
				$menu_items = wp_get_nav_menu_items( $nav_menu_id );
				foreach( $menu_items as $menu_item ){
					if( $menu_item->type == 'taxonomy' ){
						$wp_term = get_term( $menu_item->object_id, $menu_item->object );
						if( $wp_term instanceof \WP_Term ){
							self::add_url( get_term_link( $wp_term ), 3 );
							foreach( tools::get_relative_urls_by_post_or_term( $wp_term ) as $url => $priority ){
								self::add_url( $url, $priority );
							}
						}
					} elseif( $menu_item->type == 'post' ) {
						self::add_url( get_permalink( (int)$menu_item->object_id ), 3 );
						foreach( tools::get_relative_urls_by_post_or_term( get_post( $menu_item->object_id ) ) as $url => $priority ){
							self::add_url( $url, $priority );
						}
					} else {
						self::add_url( $menu_item->url, 3 );
					}
				}
			}
			///CATEGORIES
			/** @var \WP_Taxonomy $taxonomy */
			foreach( get_taxonomies( [], false ) as $taxonomy ){
				if( !$taxonomy->public || !$taxonomy->publicly_queryable ) continue;
				foreach( get_terms( [ 'taxonomy' => $taxonomy->name ] ) as $wp_term ){
					self::add_url( get_term_link( $wp_term ), 6 );
				}
			}
			///POST TYPE ARCHIVES
			foreach( get_post_types() as $post_type ){
				$post_type = get_post_type_object( $post_type );
				if( $post_type instanceof \WP_Post_Type && $post_type->public && $post_type->publicly_queryable ){
					if( $post_type->has_archive ){
						self::add_url( get_post_type_archive_link( $post_type->name ), 5 );
						console_info( get_post_type_archive_link( $post_type->name ) );
					}
				}
			}
			return $R;
		}


		/**
		 * @param $url
		 * @return array
		 */
		static function remove_url( $url ){
			$url = tools::sanitize_url( $url );
			$priority_key = self::is_url_exists( $url, true );
			if( $priority_key !== false ){
				unset( self::$urls[ $priority_key ][ $url ] );
				self::$urls_change = true;
			}
			return self::$urls;
		}


		static function get_next_urls( $limit = 5 ){
			if( !is_array( self::$urls ) ) return [];
			$limit = intval( $limit );
			if( $limit < 1 ) $limit = 1;
			self::do_sort_urls();
			$R = [];
			foreach( self::$urls as $priority => $urls ){
				foreach( $urls as $url => $microtime ){
					if( $limit < 1 ) break 2;
					//
					$microtime = intval( $microtime );
					if( $microtime + intval( options::get( 'life-time' ) ) < time() ){
						$R[] = $url;
					}
					//
					$limit --;
				}
			}
			return $R;
		}


		/**
		 * Обработать следующие по очереди URL, проверив срок жизни кэша
		 */
		static function do_process_urls(){
			$R = [];
			foreach( self::get_next_urls( self::$background_max_limit ) as $url ){
				if( !options::is_allow_url( $url ) ) continue;
				page::get_page( $url, false )->set_content();
				page::get_page( $url, true )->set_content();
				self::remove_url( $url );
				self::add_url( $url, 10, time() );
				$R[] = $url;
			}
			return $R;
		}


		/**
		 * Return url for addint them to cron
		 * @return string
		 */
		static function get_cron_url(){
			//return PathsFactory::root( false ) . '/wp-cron.php';
			return PathsFactory::root( false ) . '/wp-json/hiweb_theme/pages_cache/background';
		}


		/**
		 * Return cron string for job, add this string to you'r hosting cron queue
		 * @return string
		 */
		static function get_cron_string(){
			return cron::to_string( self::get_cron_url(), '*', '*', '*', '*', '*' );
		}


		/**
		 * @return bool
		 */
		static function is_cron_exists(){
			return cron::job_exists( self::get_cron_string() );
		}


		/**
		 * @return bool|string
		 */
		static function get_cron(){
			return cron::add_url( self::get_cron_url(), '*', '*', '*', '*', '*' );
		}


	}