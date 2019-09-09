<?php

	namespace hiweb\components\fields;


	use hiweb\core\arrays\Arrays;


	class Form{

		static function the_post_meta_box( $wp_post, $args ){
			$args = Arrays::make( $args );
			$fields = $args->_( [ 'args', 1 ], [] );
			if( is_array( $fields ) && $wp_post instanceof \WP_Post ){
				foreach( $fields as $global_id => $Field ){
					if( $Field instanceof Field ){
						?>
						<div class="hiweb-field-wrap">
							<p class="label"><strong><?=$Field->Options()->Screen()->PostType()->label()?></strong></p>
							<?php
								$Field->the_form_input( $Field->get_id(), get_post_meta( $wp_post->ID, $Field->get_id(), true ) );
							?>
							<p class="description"><?=$Field->Options()->Screen()->PostType()->label()?></p>
						</div>
						<?php

					}
				}
			}
		}

	}