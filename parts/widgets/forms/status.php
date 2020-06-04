<div class="hiweb-theme-widget-form-status-wrap" data-form-status-id="<?= get_the_form_id() ?>">
	<div class="hiweb-theme-widget-form-status">
		<div class="icon wait">
			<?= \hiweb\components\FontAwesome\FontAwesomeFactory::get(get_the_form()->get_status_icon( 'process' )) ?>
		</div>
		<div class="icon success">
			<?= \hiweb\components\FontAwesome\FontAwesomeFactory::get(get_the_form()->get_status_icon( 'success' ) )?>
		</div>
		<div class="icon warn">
			<?= \hiweb\components\FontAwesome\FontAwesomeFactory::get(get_the_form()->get_status_icon( 'warn' )) ?>
		</div>
		<div class="icon error">
			<?= \hiweb\components\FontAwesome\FontAwesomeFactory::get(get_the_form()->get_status_icon( 'error' )) ?>
		</div>
		<div class="message">
			<?= \hiweb\components\FontAwesome\FontAwesomeFactory::get(get_the_form()->get_status_message( 'process' ) )?>
		</div>
		<div class="status-close">
			<button data-form-status-close>OK</button>
		</div>
	</div>
</div>