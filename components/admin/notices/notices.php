<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 18.02.2018
	 * Time: 9:33
	 */

	namespace hiweb\admin\notices;


	class notices{

		static $notices = [];


		static public function _hook_admin_notices(){
			if( is_array( self::$notices ) ) foreach( self::$notices as $notice ){
				if( $notice instanceof notice ){
					$notice->the();
				}
			}
		}

	}