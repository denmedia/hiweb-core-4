<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 0:05
	 */

	namespace theme {
		
		
		use hiweb\components\NavMenus\NavMenusFactory;
		use hiweb\core\ArrayObject\ArrayObject;
		use theme\includes\frontend;


		class nav_menu{

			/** @var nav_menu[] */
			static $menus = [];

			public $id = 'mobile-menu';
			protected $root_classes = [];
			protected $root_tags = [];
			//public $use_ul_li = true;
			public $depth = 2;
			public $nav_location = 'mobile-menu';
			public $ul_classes = [ 'nav', 'nav-fill' ];
			public $item_classes = [ 'nav-item' ];
			public $link_classes = [ 'nav-link' ];
			public $item_class_active = 'active';
			public $items;
			public $items_by_parent;
			public $use_stellarnav = false;
			public $use_stellarnav_showArrows = true;

			///ROWS
			/** @var */
			private static $the_item;
			private static $the_item_depth = 0;
			private static $the_instance;


			/**
			 * @param $nav_location
			 * @return nav_menu
			 */
			static function get( $nav_location ){
				if( !array_key_exists( $nav_location, self::$menus ) ){
					self::$menus[ $nav_location ] = new nav_menu( $nav_location );
				}
				return self::$menus[ $nav_location ];
			}


			public function __construct( $nav_location ){
				$this->nav_location = $nav_location;
				$this->id = 'menu-' . $nav_location;
				$this->add_tag( 'data-arrows', (bool)$this->use_stellarnav_showArrows );
			}


			/**
			 * @param $class
			 * @return $this
			 */
			public function add_class( $class ){
				$this->root_classes[] = $class;
				return $this;
			}


			/**
			 * @return string
			 */
			public function get_root_classes(){
				return implode( ' ', $this->root_classes );
			}


			/**
			 * @param string $name
			 * @param string $value
			 * @return $this
			 */
			public function add_tag( $name, $value = '' ){
				$this->root_tags[ $name ] = $value;
				return $this;
			}


			/**
			 * @return string
			 */
			public function get_tags(){
				$R = [];
				foreach( $this->root_tags as $name => $value ){
					$R[] = $name . '="' . htmlentities( $value ) . '"';
				}
				return implode( ' ', $R );
			}


			/**
			 * @version 1.1
			 * @param bool $by_parent
			 * @return array
			 */
			public function get_items( $by_parent = false ){
				if( !is_array( $this->items ) ){
					$this->items = [];
					$menu_items = apply_filters( '\theme\nav_menu::get_items', NavMenusFactory::get_by_location( $this->nav_location )->get_items(), $this );
					foreach( $menu_items as $item ){
						$this->items[ (int)$item->ID ] = $item;
						$this->items_by_parent[ (int)$item->menu_item_parent ][ (int)$item->ID ] = $item;
					}
				}
				if( is_bool( $by_parent ) ){
					return $by_parent ? $this->items_by_parent : $this->items;
				} elseif( is_numeric( $by_parent ) ) {
					return array_key_exists( (int)$by_parent, $this->items_by_parent ) ? $this->items_by_parent[ (int)$by_parent ] : [];
				}
				return [];
			}


			/**
			 * @param int $ID
			 * @return bool
			 */
			public function has_subitems( $ID = 0 ){
				return array_key_exists( $ID, $this->get_items( true ) );
			}


			/**
			 * @param int $parent_id
			 * @param int $depth
			 * @version 2
			 */
			public function the_list( $parent_id = 0, $depth = 0 ){
				if( $depth <= $this->depth && $this->has_subitems( $parent_id ) ){
					$items_count = 0;
					$items_symbol_count = 0;
					//
					ob_start();
					foreach( $this->get_items( $parent_id ) as $item ){
						self::$the_item = $item;
						self::$the_item_depth = $depth;
						self::$the_instance = $this;
						//						console_info( $item );
						$templates = [];
						$templates[] = 'parts/nav_menu/the_item-' . $item->type . '-' . $item->object . '-' . $item->object_id . '.php';
						$templates[] = 'parts/nav_menu/the_item-' . $item->type . '-' . $item->object . '.php';
						$templates[] = 'parts/nav_menu/the_item-' . $item->type . '.php';
						$templates[] = 'parts/nav_menu/the_item.php';
						locate_template( $templates, true, false );
						$items_symbol_count += mb_strlen( $item->title );
						$items_count ++;
					}
					$R = ob_get_clean();
					?>
					<ul class="<?= $depth == 0 ? implode( ' ', $this->ul_classes ) : '' ?> nav-level-<?= $depth ?>" <?= $depth > 0 ? 'style="visibility: hidden;"' : '' ?> data-items-count="<?= $items_count ?>" data-items-symbols-count="<?= $items_symbol_count ?>">
						<?php
							echo $R;
						?>
					</ul>
					<?php
				}
			}


			public function the(){
				if( $this->use_stellarnav ){
					frontend::fontawesome();
					frontend::stellarnav();
					$this->root_classes[] = 'stellarnav';
					?>
					<div id="<?= $this->id ?>" <?= $this->get_tags() ?> <?= $this->get_root_classes() == '' ? '' : 'class="' . $this->get_root_classes() . '"' ?>>
					<nav>
					<?php
				} else {
					?>
					<nav id="<?= $this->id ?>" <?= $this->get_tags() ?> <?= ArrayObject::get_instance( $this->root_classes )->is_empty() ? '' : 'class="' . implode( ' ', $this->root_classes ) . '"' ?>>
					<?php
				}
				$this->the_list( 0 );
				if( !$this->use_stellarnav ){ ?></nav> <?php } else { ?>
					</nav>
					</div>
					<?php
					frontend::js( __DIR__ . '/nav_menu.min.js', frontend::jquery() );
				}
			}


			/**
			 * @return string
			 */
			public function get_html(){
				ob_start();
				$this->the();
				return ob_get_clean();
			}


			/**
			 * @return string
			 */
			public function __toString(){
				return $this->get_html();
			}


			static function the_item(){
				return self::$the_item;
			}


			static function the_item_depth(){
				return self::$the_item_depth;
			}


			/**
			 * @return nav_menu
			 */
			static function the_instance(){
				return self::$the_instance;
			}


		}
	}