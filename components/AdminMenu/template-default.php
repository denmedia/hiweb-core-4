<?php
	$Page = \hiweb\components\AdminMenu\AdminMenuFactory::the_Page();
	\hiweb\components\Includes\IncludesFactory::Css( __DIR__ . '/AdmiMenu_Page.css' );
?>
<div class="wrap hiweb-adminmenu-page-wrap">
	<h1><?= $Page->page_title() ?></h1>
	<form method="post" enctype="multipart/form-data" action="options.php">
		<?php
			settings_fields( $Page->menu_slug() );
			echo \hiweb\components\Fields\FieldsAdminFactory::get_ajax_form_html( [
				'options' => $Page->menu_slug()
			] );
		?>
		<p class="submit">
			<button type="submit" name="submit" id="submit" class="button button-primary"><?= $Page->submit_button_icon() != '' ? \hiweb\components\FontAwesome\FontAwesomeFactory::get( $Page->submit_button_icon() ) . ' ' : '' ?><?= $Page->submit_button_label() ?></button>
		</p>
	</form>
</div>
