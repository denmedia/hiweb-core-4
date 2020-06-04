<?php

	use hiweb\urls;
	use theme\includes\frontend;
	use theme\structures;


	add_action( 'widgets_init', function(){ register_widget( 'hiweb_theme_widget_menu_collapse' ); } );


	class hiweb_theme_widget_menu_collapse extends WP_Widget{

		function __construct(){
			parent::__construct( 'hiweb_theme_widget_menu_collapse', 'Навигация по категориям hiWeb', [ 'description' => 'Вывести меню с поддержкой раскрытия списка', 'customize_selective_refresh' => true ] );
		}


		/**
		 * Outputs the content for the current Navigation Menu widget instance.
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current Navigation Menu widget instance.
		 * @since 3.0.0
		 */
		public function widget( $args, $instance ){
			// Get menu
			$nav_menu = !empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

			if( !$nav_menu ){
				return;
			}

			if( theme\widgets\menu_collapse::$defer_include_scripts ){
				frontend::css( __DIR__ . '/style.css' );
				frontend::js( __DIR__ . '/app.js', frontend::jquery() );
			}

			$title = !empty( $instance['title'] ) ? $instance['title'] : '';

			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			echo $args['before_widget'];

			if( $title ){
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$nav_menu_args = [
				'fallback_cb' => '',
				'menu' => $nav_menu,
			];

			/**
			 * Filters the arguments for the Navigation Menu widget.
			 * @param array        $nav_menu_args {
			 *                                    An array of arguments passed to wp_nav_menu() to retrieve a navigation menu.
			 * @type callable|bool $fallback_cb   Callback to fire if the menu doesn't exist. Default empty.
			 * @type mixed         $menu          Menu ID, slug, or name.
			 *                                    }
			 * @param WP_Term      $nav_menu      Nav menu object for the current menu.
			 * @param array        $args          Display arguments for the current widget.
			 * @param array        $instance      Array of settings for the current widget.
			 * @since 4.2.0
			 * @since 4.4.0 Added the `$instance` parameter.
			 */
			//						wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );
			$menu_items = wp_get_nav_menu_items( $instance['nav_menu'] );
			$menu_items_by_parent = [];
			foreach( $menu_items as $item ){
				$menu_items_by_parent[ $item->menu_item_parent ][] = $item;
			}
			$this->the_items( $menu_items_by_parent );
			echo $args['after_widget'];
		}


		/**
		 * @varsion 1.2
		 * @param array $menu_items_by_parent
		 * @param int   $menu_item_parent
		 * @param int   $depth
		 * @param bool  $parent_active
		 */
		private function the_items( $menu_items_by_parent = [], $menu_item_parent = 0, $depth = 0, $parent_active = false ){
			if( $depth > 4 ) return;
			if( is_array( $menu_items_by_parent ) && isset( $menu_items_by_parent[ $menu_item_parent ] ) && count( $menu_items_by_parent[ $menu_item_parent ] ) > 0 ){
				$has_sub_active = false;
				$R = '';
				foreach( $menu_items_by_parent[ $menu_item_parent ] as $item ){
					ob_start();
					$current = structures::get( get_queried_object() )->has_object( $item );
					$active = $current || urls::get()->is_dirs_intersect( $item->url );
					$classes = [ 'menu-item', 'menu-item-type-' . $item->type, 'menu-item-' . ( isset( $menu_items_by_parent[ $item->ID ] ) ? 'has-children' : 'no-children' ), 'menu-item-' . $item->ID, 'depth-' . $depth ];
					if( $active ){
						$has_sub_active = true;
						$classes[] = 'expanded';
					}
					if( $current ){
						$classes[] = 'current';
					}
					if( $parent_active ){
						$classes[] = 'menu-item-parent-active';
					}
					$classes = apply_filters( '\hiweb_theme_widget_menu_collapse::the_items-item_classes', $classes, $item, get_defined_vars(), $this );
					///item before
					echo apply_filters( '\hiweb_theme_widget_menu_collapse::the_items-item_before', "<li id=\"menu-item-{$item->ID}\" class=\"" . join( ' ', $classes ) . "\">", $item, get_defined_vars(), $this );
					$item_title = apply_filters( '\hiweb_theme_widget_menu_collapse::the_items-item_title', "{$item->title}", $item, get_defined_vars(), $this );
					if( $item->url == '#' || $item->url == '' ){
						$item_content = apply_filters( '\hiweb_theme_widget_menu_collapse::the_items-item_span', "<span href=\"{$item->url}\" class=\"item-link\">{$item_title}</span>", $item, get_defined_vars(), $this );
					} else {
						$item_content = apply_filters( '\hiweb_theme_widget_menu_collapse::the_items-item_a', "<a href=\"{$item->url}\" class=\"item-link\"><span class='item-text'>{$item_title}</span></a>", $item, get_defined_vars(), $this );
					}
					echo apply_filters( '\hiweb_theme_widget_menu_collapse::the_items-item_content', $item_content, $item, get_defined_vars(), $this );
					///sub items
					$this->the_items( $menu_items_by_parent, $item->ID, $depth + 1, $active );
					///item after
					echo apply_filters( '\hiweb_theme_widget_menu_collapse::the_items-item_after', "</li>", $item, get_defined_vars(), $this );
					$R .= apply_filters( '\hiweb_theme_widget_menu_collapse::the_items-item_html', ob_get_clean(), $item, get_defined_vars(), $this );
				}
				$json_options = null;
				if( $depth == 0 ){
					$json_options = [
						'icon_expand' => get_field( 'icon-expand', \theme\widgets\menu_collapse::$options_handle ),
						'icon_collapse' => get_field( 'icon-collapse', \theme\widgets\menu_collapse::$options_handle )
					];
					$json_options = 'data-options="' . htmlentities( json_encode( $json_options ) ) . '"';
				}
				?>
				<ul class="<?= $depth == 0 ? 'menu' : 'sub-menu' ?>" <?= ( $has_sub_active || $parent_active || $depth == 0 ) ? '' : 'style="display: none;"' ?> <?= $json_options ?>>
					<?= apply_filters( '\hiweb_theme_widget_menu_collapse::the_items-items', $R, $this, get_defined_vars() ); ?>
				</ul>
				<?php
			}
		}


		/**
		 * Handles updating settings for the current Navigation Menu widget instance.
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 * @return array Updated settings to save.
		 * @since 3.0.0
		 */
		public function update( $new_instance, $old_instance ){
			$instance = [];
			if( !empty( $new_instance['title'] ) ){
				$instance['title'] = sanitize_text_field( $new_instance['title'] );
			}
			if( !empty( $new_instance['nav_menu'] ) ){
				$instance['nav_menu'] = (int)$new_instance['nav_menu'];
			}
			return $instance;
		}


		/**
		 * Outputs the settings form for the Navigation Menu widget.
		 * @param array                 $instance Current settings.
		 * @since 3.0.0
		 * @global WP_Customize_Manager $wp_customize
		 */
		public function form( $instance ){
			global $wp_customize;
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

			// Get menus
			$menus = wp_get_nav_menus();

			$empty_menus_style = $not_empty_menus_style = '';
			if( empty( $menus ) ){
				$empty_menus_style = ' style="display:none" ';
			} else {
				$not_empty_menus_style = ' style="display:none" ';
			}

			$nav_menu_style = '';
			if( !$nav_menu ){
				$nav_menu_style = 'display: none;';
			}

			// If no menus exists, direct the user to go and create some.
			?>
			<p class="nav-menu-widget-no-menus-message" <?php echo $not_empty_menus_style; ?>>
				<?php
					if( $wp_customize instanceof WP_Customize_Manager ){
						$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
					} else {
						$url = admin_url( 'nav-menus.php' );
					}
				?>
				<?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) ); ?>
			</p>
			<div class="nav-menu-widget-form-controls" <?php echo $empty_menus_style; ?>>
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
					<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
					<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
						<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
						<?php foreach( $menus as $menu ) : ?>
							<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
								<?php echo esc_html( $menu->name ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</p>
				<?php if( $wp_customize instanceof WP_Customize_Manager ) : ?>
					<p class="edit-selected-nav-menu" style="<?php echo $nav_menu_style; ?>">
						<button type="button" class="button"><?php _e( 'Edit Menu' ); ?></button>
					</p>
				<?php endif; ?>
			</div>
			<?php
		}
	}
