<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 18.02.2018
	 * Time: 9:33
	 */

	namespace hiweb\admin\notices;


	class notice{

		public $title = '';
		public $content = '&nosp;';
		public $class = '';
		private $CLASS_;


		/**
		 * notice constructor.
		 */
		public function __construct( $content = '&nosp;' ){
			$this->content = $content;
		}


		public function the(){
			?>
			<div class="<?= $this->class ?> notice">
				<?php if( trim( $this->title ) != '' ){ ?><p class="notice-title"><?= $this->title ?></p><?php } ?>
				<p><?= $this->content ?></p>
			</div>
			<?php
		}


		public function CLASS_(){
			if( !$this->CLASS_ instanceof notice_class ){
				$this->CLASS_ = new notice_class( $this );
			}
			return $this->CLASS_;
		}
	}