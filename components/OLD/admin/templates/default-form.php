<?php

	/**
	 * @var \hiweb\admin\pages\page_abstract $this
	 */

	use hiweb\fields\locations\locations;


	if( isset( $_GET['settings-updated'] ) ){
		if( $_GET['settings-updated'] ){
			if( get_current_screen()->parent_file != 'options-general.php' ){
				$notice = add_admin_notice( 'Для страницы "' . $this->page_title() . '" все данные успешно сохранены' );
				$notice->CLASS_()->success();
				$notice->the();
			}
		} else {
			$notice = add_admin_notice( 'Ошибка в момент сохранения опций' );
			$notice->CLASS_()->error();
			$notice->the();
		}
	}

?>
<div class="wrap">
	<h1><?= $this->page_title() ?></h1>
	<form method="post" enctype="multipart/form-data" action="options.php">
		<?php
			settings_fields( $this->menu_slug() );
			$fields = locations::get_fields_by_contextObject( $this->menu_slug() );
			\hiweb\fields\forms::the_form_by_contextObject( $this->menu_slug() );
			if( is_callable( $this->function_page() ) ){
				call_user_func( $this->function_page(), $this->function_params, $this );
			}
		?>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>"/>
		</p>
	</form>
</div>