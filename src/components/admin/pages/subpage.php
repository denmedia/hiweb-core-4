<?php

	namespace hiweb\admin\pages;


	class subpage extends page_abstract{

		protected $parent_slug = '';


		/**
		 * @param string $set
		 * @return string
		 */
		public function parent_slug($set = null){
			return $this->set(__FUNCTION__, $set);
		}

	}