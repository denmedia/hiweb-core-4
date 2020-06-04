<?php

	use theme\pages_cache;
	use theme\pages_cache\options;
	use theme\pages_cache\queue;


	if( is_array( $_POST ) && count( $_POST ) > 0 && is_user_logged_in() ){
		if( isset( $_POST['add-cron'] ) ){
			$string = queue::get_cron();
			if( is_string( $string ) ){
				$notice = hiweb\admin::NOTICE( 'Задача CRON хостинга добавлена.' );
				$notice->CLASS_()->success();
				$notice->the();
			} else {
				$notice = hiweb\admin::NOTICE( 'Не удалось добавить задачу в CRON хостинга. Сделайте это вручную!' );
				$notice->CLASS_()->error();
				$notice->the();
			}
		} elseif( isset( $_POST['clear-cache'] ) ) {
			pages_cache\cache::do_clear_all();
			queue::clear();
			queue::set_default_urls();
			queue::do_sort_urls();
		} elseif( isset( $_POST['clear-queue'] ) ) {
			queue::clear();
		} else {
			if( !array_key_exists( 'enable', $_POST ) ){
				options::set( 'enable', '' );
			}
			if( !array_key_exists( 'enable-background', $_POST ) ){
				options::set( 'enable-background', '' );
			}
			foreach( $_POST as $key => $value ){
				options::set( $key, $value );
			}
			options::update();
		}
	}

?>
<h1><i class="fas fa-car-battery"></i> Pages cache - Установки кэширования страниц</h1>
<div class="hiweb-theme-pages-cache-admin-menu">
	<form action="<?= get_url()->get() ?>" method="post">

		<table class="form-table">
			<tbody>
			<tr>
				<th>ВКЛЮЧИТЬ КЭШИРОВАНИЕ</th>
				<td>
					<label>
						<input name="enable" type="checkbox" <?= options::is_enable() ? 'checked' : '' ?>>
						Включите данный пункт, чтобы использовать ранее созданный кэш и создавать новый</label>
				</td>
			</tr>
			<tr>
				<th>ФОНОВОЕ СОЗДАНИЕ КЭША</th>
				<td>
					<label>
						<input name="enable-background" type="checkbox" <?= options::is_background_enable() ? 'checked' : '' ?>>
						Включите данный пункт, чтобы кэш автоматически создавался и обновлялся в фоне</label>
				</td>
			</tr>
			<tr>
				<th>Времи жизни кэша в секундах</th>
				<td><input class="regular-text code" type="text" name="life-time" value="<?= options::get( 'life-time', 3600 ) ?>">
					<p class="description"><code>3600</code> = час, <code>18400</code> = сутки, <code>604800</code> = неделя</p></td>
			</tr>
			<tr>
				<th scope="row">Запрещенные страницы для кэша</th>
				<td>
					<textarea class="large-text code" name="disabled_urls" rows="10"><?= implode( "\n", options::get_disabled_urls() ) ?></textarea>
					<p class="description">Укажите построчно запрещенные к кэшированию URL адреса. Так же возможно использовать знак <code>*</code>, чтобы включить маску.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="mailserver_url">Динамические элементы</label></th>
				<td>
					<textarea class="large-text code" name="dynamic_selectors" rows="10"><?= implode( "\n", options::get_dynamic_selectos() ) ?></textarea>
					<p class="description">Укажите динамические селекторы HTML элементов, которые будут подгружены не из кэша. К примеру корзины или баннеры.</p>
				</td>
			</tr>
			</tbody>
		</table>


		<p>
			<button class="button button-primary button-large" type="submit">Сохранить настройки</button>
		</p>
	</form>

	<hr>

	<table class="form-table">
		<tbody>
		<tr>
			<th>
				Задача в CRON хостинга:
			</th>
			<td>
				<?php

					if( defined( 'DISABLE_WP_CRON' ) && !DISABLE_WP_CRON ){
						?>
						<p style="color: red; font-weight: bolder">
							На Вашем сайте программно выключен WP CRON! Проверьте файл wp-config.php на наличие установленно константы <code>DISABLE_WP_CRON</code> - ее нужно удалить. Так же данная константа может быть установлена в любом другом файле
						</p>
						<?php
					} else {
						$cron_string = queue::get_cron_string();
						if( false && \hiweb\components\Cron::job_exists( $cron_string ) ){
							echo '<code>' . queue::get_cron_url() . '</code>';
						} else {
							?><p style="color: red; font-weight: bolder">не создана! она необходима для создания кэша в фоне!</p>
							<form action="<?= get_url()->get() ?>" method="post">
								<p>
									<input type="hidden" name="add-cron">
									<button class="button button-primary">Создать CRON</button>
								</p>
							</form>
							<p class="description">Если задачу в расписании не удалось создать с помощью данной кнопки, сделайте это вручную в своей хостинг-панеле! Для этого добавьте в Ваш планировщик CRON на хостинге следующую строчку: <code><?= $cron_string ?></code></p><?php
						}
					}

				?>
			</td>
		</tr>
		<tr>
			<th>
				Очередь страниц на кэширование
				<p class="description">список URL страниц в очереди на фоновое создание кэша в порядке приоритета. Цифра вначале - приоритет. Приоритет '0' - означает, что кэш был создан.</p>
			</th>
			<td><textarea class="large-text code" rows="15" disabled><?php
						$urls = queue::get_urls();
						//asort( $urls, SORT_NUMERIC );
						foreach( $urls as $priority => $urls_data ){
							foreach( $urls_data as $url => $microtime ){
								echo $priority . ':' . $microtime . ':' . $url . "\n";
							}
						}
					?></textarea></td>
		</tr>
		</tbody>
	</table>
	<form action="<?= get_url()->get() ?>" method="post">
		<p>
			<input type="hidden" name="clear-queue">
			<button class="button button-large" type="submit">Сбросить очередь</button>
		</p>
	</form>

	<hr>

	<form action="<?= get_url()->get() ?>" method="post">
		<table class="form-table">
			<tbody>
			<tr>
				<th>
					Размер и количество файлов кэша
				</th>
				<td>Размер: <b><?= get_path( pages_cache\cache::get_dir() )->file()->get_size_formatted() ?></b> / <?= count( get_path( pages_cache\cache::get_dir() )->file()->get_sub_files() ) ?> файлов</td>
			</tr>
			</tbody>
		</table>
		<p>
			<input type="hidden" name="clear-cache">
			<button class="button button-primary button-large" type="submit">Сбросить весь кэш</button>
		</p>
	</form>
</div>