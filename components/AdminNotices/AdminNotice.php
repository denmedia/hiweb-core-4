<?php
	
	namespace hiweb\components\AdminNotices;
	
	
	use hiweb\core\hidden_methods;
	
	
	class AdminNotice{
		
		use hidden_methods;
		
		protected $title = '';
		protected $content = '&nosp;';
		protected $class = '';
		protected $options;
		
		
		/**
		 * notice constructor.
		 * @param string $content
		 * @param string $title
		 */
		public function __construct( $content = '&nosp;', $title = '' ){
			$this->title = $title;
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
		
		
		/**
		 * @return AdminNotice_Options
		 */
		public function options(){
			if( !$this->options instanceof AdminNotice_Options ){
				$this->options = new AdminNotice_Options( $this );
			}
			return $this->options;
		}
	}