<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 30.05.2018
	 * Time: 16:30
	 */

	if ( ! function_exists( 'get_remote_video' ) ) {

		/**
		 * Return video class from url like ""
		 *
		 * @param $url
		 *
		 * @return \hiweb\remote_videos\video
		 */
		function get_remote_video( $url ) {
			return \hiweb\remote_videos::get_video( $url );
		}
	} else {
		hiweb\console::debug_warn( 'Function [get_remote_video] is exists...' );
	}