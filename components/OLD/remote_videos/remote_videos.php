<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 30.05.2018
	 * Time: 16:19
	 */

	namespace hiweb;


	class remote_videos{

		static private $videos = [];

		/**
		 * @param $url
		 *
		 * @return remote_videos\video
		 */
		static function get_video( $url ) {
			if ( ! isset( self::$videos[ $url ] ) ) {
				self::$videos[ $url ] = new remote_videos\video( $url );
			}

			return self::$videos[ $url ];
		}

	}