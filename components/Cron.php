<?php

	namespace hiweb\components;


	use hiweb\core\Strings;


	class Cron{

		/**
		 * @param string $jobs
		 * @return array
		 */
		static private function string2array( $jobs = '' ){
			$array = explode( "\r\n", trim( $jobs ) ); // trim() gets rid of the last \r\n
			foreach( $array as $key => $item ){
				if( $item == '' ){
					unset( $array[ $key ] );
				}
			}
			return $array;
		}


		/**
		 * @param array $jobs
		 * @return string
		 */
		static private function array2string( $jobs = [] ){
			return implode( "\r\n", $jobs );
		}


		/**
		 * @return array
		 */
		static function get_jobs(){
			$output = shell_exec( 'crontab -l' );
			return self::string2array( $output );
		}


		/**
		 * @param array $jobs
		 * @return string
		 */
		static function save_jobs( $jobs = [] ){
			$output = shell_exec( 'echo "' . self::array2string( $jobs ) . '" | crontab -' );
			return $output;
		}


		/**
		 * @param string $job
		 * @return bool
		 */
		static function job_exists( $job = '' ){
			$jobs = self::get_jobs();
			foreach( $jobs as $check_job ){
				if( strpos( $job, $check_job ) !== false ){
					return true;
				}
				if( strpos( $check_job, $job ) !== false ){
					return true;
				}
			}
			//			if( in_array( $job, $jobs ) ){
			//				return true;
			//			} else {
			//				return false;
			//			}
			return false;
		}


		/**
		 * @param string $job
		 * @return bool|string
		 */
		static function add_job( $job = '' ){
			if( self::job_exists( $job ) ){
				return false;
			} else {
				$jobs = self::get_jobs();
				$jobs[] = $job;
				return self::save_jobs( $jobs );
			}
		}


		/**
		 * @param string $job
		 * @return bool|string
		 */
		static function remove_job( $job = '' ){
			if( Strings::is_regex( $job ) ){
				$jobs = self::get_jobs();
				foreach( $jobs as $j ){
					if( preg_match( $job, $j ) > 0 ) unset( $jobs[ array_search( $job, $jobs ) ] );
				}
				return self::save_jobs( $jobs );
			} else {
				if( self::job_exists( $job ) ){
					$jobs = self::get_jobs();
					unset( $jobs[ array_search( $job, $jobs ) ] );
					return self::save_jobs( $jobs );
				} else {
					return false;
				}
			}
		}


		/**
		 * @return string
		 */
		static function clear_jobs(){
			return exec( 'crontab -r', $crontab );
		}


		/**
		 * @param        $url
		 * Пояснение установки расписания
		 * @param string $minutes
		 * @param string $hours
		 * @param string $days
		 * @param string $mounts
		 * @param string $weeks
		 * @param bool   $notify_email
		 * @return bool|string
		 * @see https://help.ubuntu.ru/wiki/cron
		 */
		static function add_url( $url, $minutes = '0', $hours = '*', $days = '*', $mounts = '*', $weeks = '*', $notify_email = false ){
			$job_string = self::to_string( $url, $minutes, $hours, $days, $mounts, $weeks, $notify_email );
			self::add_job( $job_string );
			return $job_string;
		}


		/**
		 * @param        $url
		 * @param string $minutes
		 * @param string $hours
		 * @param string $days
		 * @param string $mounts
		 * @param string $weeks
		 * @param bool   $notify_email
		 * @param string $bin - 'wget --quiet -O /dev/null' | 'curl'
		 * @return string
		 */
		static function to_string( $url, $minutes = '0', $hours = '*', $days = '*', $mounts = '*', $weeks = '*', $notify_email = true, $bin = 'wget --quiet -O /dev/null' ){
			//return $minutes . ' ' . $hours . ' ' . $days . ' ' . $mounts . ' ' . $weeks . ' wget --quiet -O /dev/null ' . $url.($notify_email ? '' : '  >/dev/null 2>&1');
			return $minutes . ' ' . $hours . ' ' . $days . ' ' . $mounts . ' ' . $weeks . ' ' . $bin . ' ' . $url . ( $notify_email ? '' : '  >/dev/null 2>&1' );
		}

	}