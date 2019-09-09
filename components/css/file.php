<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 02:44
	 */

	namespace hiweb\css;


	use hiweb\arrays;
	use hiweb\paths\path;


	class file extends path{

		private $rel;
		private $media;
		private $deeps = [];
		private $tags = [];

		private $footer = false;


		/**
		 * @return $this
		 */
		public function put_to_footer(){
			$this->footer = true;
			return $this;
		}


		/**
		 * @return bool
		 */
		public function is_in_footer(){
			return $this->footer;
		}


		/**
		 * Add deeps styles
		 * @param null|string|array $deeps
		 * @return array
		 */
		public function add_deeps( $deeps = null ){
			if( !is_null( $deeps ) ){
				$deeps = is_array( $deeps ) ? $deeps : [ $deeps ];
				$this->deeps = array_merge( $this->deeps, $deeps );
			}
			return $this->deeps;
		}


		/**
		 * @return array
		 */
		public function get_deeps(){
			return $this->deeps;
		}


		/**
		 * @return media
		 */
		public function media(){
			if( !$this->media instanceof media ){
				$this->media = new media( $this );
			}
			return $this->media;
		}


		/**
		 * @return rel
		 */
		public function rel(){
			if( !$this->rel instanceof rel ){
				$this->rel = new rel( $this );
			}
			return $this->rel;
		}


		/**
		 * Return (echo) link rel html
		 * @return null|string
		 */
		public function html(){
			ob_start();
			$this->the();
			return ob_get_clean();
		}


		/**
		 * @param $key
		 * @param null|string $value - null for delete tag
		 */
		public function add_tag( $key, $value ){
			if( is_null( $value ) ) unset( $this->tags[ $key ] ); else $this->tags[ $key ] = $value;
		}


		/**
		 * @param bool $return_array
		 * @return array|string
		 */
		public function get_tags( $return_array = false ){
			if( $return_array ){
				return $this->tags;
			} else {
				return Arrays::make( $this->tags )->get_param_html_tags();
			}
		}


		/**
		 *
		 */
		public function the(){
			?>
			<link <?= $this->rel()->get() ?> <?= $this->get_tags() ?> type="text/css" href="<?= $this->get_url() ?>" <?= $this->media()->get() ?>/><?php
		}


	}