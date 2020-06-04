<?php

	use theme\forms;


	add_action( 'widgets_init', function(){ register_widget( 'hiweb_theme_form' ); } );


	class hiweb_theme_form extends WP_Widget{

		function __construct(){
			parent::__construct( 'hiweb_theme_form', '<i></i>Формы на сайте', [ 'description' => 'Вывести форму или кнопку с формой в попапе в виде виджета', 'customize_selective_refresh' => true ] );
		}


		/**
		 * Outputs the content for the current Navigation Menu widget instance.
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current Navigation Menu widget instance.
		 * @since 3.0.0
		 */
		public function widget( $args, $instance ){
			$instance = get_array( $instance );
			$form = get_form( $instance->get_value( 'id' ) );
			if( $form->is_exists() ){
				if( $instance->get_value( 'fancybox' ) != '' ){
					$button_text = $instance->get_value( 'button-text' );
					$button_text = $button_text == '' ? $form->get_wp_post()->post_title : $button_text;
					$form->the_fancybox_button( $button_text );
				} else {
					$form->the();
				}
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
			if( !empty( $new_instance['button-text'] ) ){
				$instance['button-text'] = $new_instance['button-text'];
			}
			if( !empty( $new_instance['id'] ) ){
				$instance['id'] = (int)$new_instance['id'];
			}
			$instance['fancybox'] = !empty( $new_instance['fancybox'] ) ? $new_instance['fancybox'] : '';
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
			$button_text = isset( $instance['button-text'] ) ? $instance['button-text'] : '';
			$form_id = isset( $instance['id'] ) ? $instance['id'] : '';
			$fancybox = isset( $instance['fancybox'] ) && $instance['fancybox'] != '';

			// If no menus exists, direct the user to go and create some.
			?>
			<div class="nav-menu-widget-form-controls">
				<p>
					<label for="<?= $this->get_field_id( 'id' ) ?>">Какую форму выводить?</label>
					<select class="widefat" name="<?= $this->get_field_name( 'id' ) ?>" id="<?= $this->get_field_id( 'id' ) ?>">
						<option value="">--выберите форму--</option>
						<?php
							foreach( get_posts( [ 'post_type' => forms::$post_type_name ] ) as $wp_post ){
								$selected = $form_id == $wp_post->ID;
								?>
								<option <?= $selected ? 'selected' : '' ?> value="<?= $wp_post->ID ?>"><?= $wp_post->post_title ?> (id:<?= $wp_post->ID ?>)</option>
								<?php
							}
						?>
					</select>
				</p>
				<p>
					<label for="<?= $this->get_field_id( 'fancybox' ) ?>"><input <?= $fancybox ? 'checked' : '' ?> type="checkbox" id="<?= $this->get_field_id( 'fancybox' ) ?>" name="<?= $this->get_field_name( 'fancybox' ) ?>"/> Форму выводить в попапе с кнопкой</label>
				</p>
				<p>
					<label for="<?= $this->get_field_id( 'button-text' ) ?>">Текст на попап-кнопке</label>
					<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'button-text' ); ?>" name="<?php echo $this->get_field_name( 'button-text' ); ?>" value="<?php echo esc_attr( $button_text ); ?>"/>
				</p>
			</div>
			<?php
		}
	}
