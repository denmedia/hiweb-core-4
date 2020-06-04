<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-19
	 * Time: 10:52
	 */

	use theme\languages\multisites;


	if( get_current_network_id() == get_main_network_id() ){
		\theme\includes\admin::fontawesome();
		add_admin_menu_page( \theme\languages::$options_page_slug . '-multisite-migrate', '<i class="fas fa-plane-departure"></i> Экспорт локализированных статей', 'tools.php' )->use_default_form( false )->function_page( function(){
			?>
			<div class="wrap">
				<h1><i class="fas fa-plane-departure"></i> Экспорт статей из текущей версии сайта для сети мультисайтов</h1>
				<form action="" method="post">
					<?php
						if( !isset( $_POST['step'] ) ){
							///STEP 1
							$options = [];
							foreach( multisites::get_site_ids_by_lang_id() as $lang_id => $site_id ){
								if( $site_id == get_current_blog_id() )
									continue;
								$wp_site = get_site( $site_id );
								if( $wp_site instanceof WP_Site ){
									$options[ $site_id ] = $lang_id . ': ' . $wp_site->domain;
								}
							}

							?>
								<div>
									Выберите целевой сайт:
									<label>
										<select name="site_id">
											<?php
												foreach( $options as $val => $title ){
													?>
													<option value="<?= $val ?>"><?= $title ?></option>
													<?php
												}
											?>
										</select>
									</label>
									<p class="description">Выберите целевой сайт, на который будут экспортированны соответствующие языку статьи и страницы. Перед экспортом обязательно нужно в настройках локалии целевого сайта необходимо указать индификатор языка.</p>
								</div>
								<div>
									Выберите нужные типы записей:
									<?php
										$exclude_post_type = ['attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request',''];
										foreach(get_post_types() as $post_type) {
											if(array_key_exists($post_type,array_flip($exclude_post_type))) continue;
											?>
											<div><label><input type="checkbox" name="post_types[]" value="<?=$post_type?>"><?=$post_type?></label></div>
											<?php
										}
									?>
									<p><label><input type="checkbox" name="options" value="true">Опции (шапка и футер и прочие)</label></p>
									<p><label><input type="checkbox" name="users" value="true">Пользователи (существующие пользователи будут допущены к целевому сайту)</label></p>
								</div>
								<div>
									<input type="hidden" name="step" value="1">
									<button class="button">Предварительный просмотр эекспортируемых статей</button>
								</div>
							<?php
						} elseif( $_POST['step'] == '1' ) {
							$site_ids_by_lang_id = theme\languages\multisites::get_site_ids_by_lang_id();
							$lang_ids_by_site_id = array_flip( $site_ids_by_lang_id );
							if( !array_key_exists( $_POST['site_id'], $lang_ids_by_site_id ) ){
								?>
								<h2>Не верно указан ID сайта[<?= $_POST['site_id'] ?>] для целевого сайта</h2>
								<p>Содайте новый сайт в разделе <a href="<?= network_admin_url( '' ) ?>">САЙТЫ</a></p>
								<?php
							} else {
								\theme\includes\admin::bootstrap();
								\theme\includes\admin::js(__DIR__.'/admin-multisites.min.js', \theme\includes\admin::jquery());
								$ids = [];
								foreach($_POST['post_types'] as $post_type) {
									$posts_args = [
										'post_type' => $post_type,
										'posts_per_page' => - 1,
										'post_status' => 'any',
										'fields' => 'ids',
										'orderby' => 'rand'
									];
									if(\theme\languages::is_post_type_allowed($post_type)) {
										$posts_args['meta_query'] = [
											[
												'key' => \theme\languages::$post_meta_key_lang_id,
												'value' => $lang_ids_by_site_id[ $_POST['site_id'] ]
											]
										];
									}
									$wp_query = new WP_Query( $posts_args );
									$ids = array_merge($ids, $wp_query->get_posts());
								}
								$attachment_ids = [];
								if(array_key_exists('attachment',array_flip($_POST['post_types']))) {
									//$attachment_ids = get_posts(['post_type' => 'attachment','posts_per_page' => -1,'fields' => 'ids']);
									//$ids = array_merge($attachment_ids, $wp_query->get_posts());
								}
								///OPTIONS
								$fields_from_to = [];
								if(isset($_POST['options'])) {
									multisites::do_migrate_options_to_site($_POST['site_id']);
								}

								if(isset($_POST['users'])) {
									multisites::do_migrate_current_users_to_site($_POST['site_id']);
								}

								?>
									<p>Найдено статей/страниц/записей: <b><?= count($ids) ?></b></p>
									<script>let hiweb_language_post_ids = <?=json_encode( $ids )?>;</script>
									<script>let hiweb_language_options = <?=json_encode( $fields_from_to )?>;</script>
									<div class="figure-caption" style="height: 35px">
										<div class="progress" data-language-progress style="height: 25px">
											<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div class="jumbotron">
										Пример некоторых статей
										<?php
											$limit = 5;
											if($wp_query instanceof WP_Query){
											foreach($wp_query->get_posts() as $wp_post_id) {
												$wp_post = get_post($wp_post_id);
												if(!$wp_post instanceof WP_Post) continue;
												?><a class="d-block" href="<?=get_edit_post_link($wp_post_id)?>" target="_blank"><?=$wp_post->post_title?></a><?php
												$limit --;
												if($limit < 0) break;
											}
											}else{
												?><p>Нет экспортируемых записей</p><?php
											}
										?>
									</div>
									<input type="hidden" name="site_id" value="<?=htmlentities($_POST['site_id'])?>" />
									<button data-multisites-import class="button button-primary">Начать экспортирование статей на другую локализацию</button>
								<?php
							}
						}

					?>

				</form>
			</div>
			<?php
		} );
	}

