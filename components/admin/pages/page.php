<?php

	namespace hiweb\admin\pages;


	class page extends page_abstract{


		protected $icon_url = 'dashicons-admin-generic';
		protected $position = 85;


		/**
		 * @param null $set
		 * @return page|null
		 */
		public function icon_url( $set = null ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return page|null
		 */
		public function position( $set = null ){
			return $this->set( __FUNCTION__, $set );
		}

	}