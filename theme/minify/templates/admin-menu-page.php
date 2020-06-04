<?php

	use hiweb\admin;
	use theme\_minify\cache;
	use theme\minify;


	if( is_array( $_POST ) && count( $_POST ) > 0 && is_user_logged_in() ){
		if( isset( $_POST['clear-cache'] ) ){
			if($_POST['clear-cache'] == 'css'){
				\theme\_minify\cache::do_clear_css();
				$notice = hiweb\admin::NOTICE( 'Весь кэш CSS очищен' );
				$notice->CLASS_()->success();
				$notice->the();
			}
			elseif($_POST['clear-cache'] == 'js'){
				\theme\_minify\cache::do_clear_js();
				$notice = hiweb\admin::NOTICE( 'Весь кэш JS очищен' );
				$notice->CLASS_()->success();
				$notice->the();
			}else{
				\theme\_minify\cache::do_clear_all();
				$notice = hiweb\admin::NOTICE( 'Весь кэш CSS, JS очищен' );
				$notice->CLASS_()->success();
				$notice->the();
			}
		} else {
			//Update options
			foreach( [ 'hiweb_theme_minify_js_enable', 'hiweb_theme_minify_css_enable', 'hiweb_theme_minify_critical_css_enable' ] as $key ){
				update_option( $key, isset( $_POST[ $key ] ) );
			}
			update_option( 'hiweb_theme_minify_cache_refresh_time', $_POST['hiweb_theme_minify_cache_refresh_time'] );
			///
			minify::$js_enable = get_option( 'hiweb_theme_minify_js_enable', true );
			minify::$css_enable = get_option( 'hiweb_theme_minify_css_enable', true );
			minify::$critical_css_enable = get_option( 'hiweb_theme_minify_critical_css_enable', true );
			minify::$cache_refresh_time = get_option( 'hiweb_theme_minify_cache_refresh_time', 86400 );
			///
			$notice = hiweb\admin::NOTICE( 'Настройки сохранены' );
			$notice->CLASS_()->success();
			$notice->the();
		}
	}

?>
<h1><i class="fas fa-forklift"></i> Минификация и объединение CSS, JS - Настройки.</h1>
<div class="hiweb-theme-pages-cache-admin-menu">
	<form action="<?= get_url()->get() ?>" method="post">

		<table class="form-table">
			<tbody>
			<tr>
				<th>Объединять JS файлы</th>
				<td>
					<label>
						<input name="hiweb_theme_minify_js_enable" type="checkbox" <?= minify::$js_enable ? 'checked' : '' ?>>
						Использовать минификацию и объединение файлов JavaScript</label>
				</td>
			</tr>
			<tr>
				<th>Объединять CSS файлы</th>
				<td>
					<label>
						<input name="hiweb_theme_minify_css_enable" type="checkbox" <?= minify::$css_enable ? 'checked' : '' ?>>
						Использовать минификацию и объединение файлов стилей CSS</label>
				</td>
			</tr>
			<tr>
				<th>Критические CSS стили</th>
				<td>
					<label>
						<input name="hiweb_theme_minify_critical_css_enable" type="checkbox" <?= minify::$critical_css_enable ? 'checked' : '' ?>>
						Использовать функцию создания критических стилей и последующее размещение в шапке сайта</label>
				</td>
			</tr>
			<tr>
				<th>Времи жизни кэша скриптов и стилей в секундах</th>
				<td><input class="regular-text code" type="text" name="hiweb_theme_minify_cache_refresh_time" value="<?= minify::$cache_refresh_time ?>">
					<p class="description"><code>3600</code> = час, <code>18400</code> = сутки, <code>604800</code> = неделя</p></td>
			</tr>
			<!--<tr>
				<th scope="row">Запрещенные страницы для кэша</th>
				<td>
					<textarea class="large-text code" name="disabled_urls" rows="10" placeholder="не активно"></textarea>
					<p class="description">Укажите построчно запрещенные к кэшированию URL адреса. Так же возможно использовать знак <code>*</code>, чтобы включить маску.</p>
				</td>
			</tr>-->
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
				Размер и количество файлов кэша
			</th>
			<td>Размер: <b><?= get_path( theme\_minify\cache::get_dir() )->file()->get_size_formatted() ?></b> / <?= count( get_path( theme\_minify\cache::get_dir() )->file()->get_sub_files() ) ?> файлов</td>
		</tr>
		</tbody>
	</table>
	<form action="<?= get_url()->get() ?>" method="post" style="float: left;">
		<input type="hidden" name="clear-cache" value="css">
		<button class="button button-large" type="submit">Сбросить CSS кэш</button>
	</form>&nbsp;
	<form action="<?= get_url()->get() ?>" method="post" style="float: left;">
		<input type="hidden" name="clear-cache" value="js">
		<button class="button button-large" type="submit">Сбросить JS кэш</button>
	</form>&nbsp;
	<form action="<?= get_url()->get() ?>" method="post" style="float: left;">
		<input type="hidden" name="clear-cache">
		<button class="button button-primary button-large" type="submit">Сбросить весь кэш</button>
	</form>
</div>