<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/11/2018
	 * Time: 19:27
	 */

	use theme\languages;


	if( \hiweb\components\Context::is_admin_page() ){
		include_css( HIWEB_DIR_VENDORS . '/font-awesome-5/css/all.min.css' );
	}
	$admin_menu = add_admin_menu_page( languages::$options_page_slug, '<i class="far fa-globe-africa"></i> Локализации', 'options-general.php' );
	$admin_menu->page_title( '<i class="far fa-globe-africa"></i> Управление языками на сайте' );

	if( languages\detect::is_wp_user_multisite() ){
		add_field_checkbox( 'multisite' )->label_checkbox( 'Использовать мультисайт (поддомены), как способ переключиться между языками' )->description( 'После включения данной опции необходимо сохранить настройки. После обновления страницы появятся варианты переключения.' )->location()->ADMIN_MENUS( languages::$options_page_slug );
	}

	if( languages\detect::is_multisite() ){
		add_field_separator( 'Текущий язык сайта' )->location()->ADMIN_MENUS( languages::$options_page_slug );
		add_field_text( 'default-id' )->label( 'ID текущего языка' )->VALUE( 'ru' )->get_parent_field()->location( true );
		add_field_text( 'default-name' )->label( 'Название текущего языка' )->VALUE( 'русский язык' )->get_parent_field()->location( true );
		add_field_text( 'default-locale-name' )->label( 'Имя текущей локалии' )->description( 'Для английского языка это <code>en_GB</code>, <a href="http://support.sas.com/documentation/cdl/en/nlsref/61893/HTML/default/viewer.htm#a002613623.htm" target="_blank">ТАБЛИЦА ЛОКАЛЕЙ</a>' )->VALUE( 'ru_RU' )->get_parent_field()->location( true );
		add_field_text( 'default-title' )->label( 'Заголовок для смены языка' )->VALUE( 'русский' )->get_parent_field()->location( true );
	} else {
		add_field_separator( 'Стандартный язык сайта' )->location()->ADMIN_MENUS( languages::$options_page_slug );

		add_field_text( 'default-id' )->label( 'ID стандартного языка' )->VALUE( 'ru' )->get_parent_field()->location( true );
		add_field_text( 'default-name' )->label( 'Название стандартного языка' )->VALUE( 'русский язык' )->get_parent_field()->location( true );
		add_field_text( 'default-locale-name' )->label( 'Имя стандартной локалии' )->description( 'Для английского языка это <code>en_GB</code>, <a href="http://support.sas.com/documentation/cdl/en/nlsref/61893/HTML/default/viewer.htm#a002613623.htm" target="_blank">ТАБЛИЦА ЛОКАЛЕЙ</a>' )->VALUE( 'ru_RU' )->get_parent_field()->location( true );
		add_field_text( 'default-title' )->label( 'Заголовок для смены языка' )->VALUE( 'русский' )->get_parent_field()->location( true );

		add_field_separator( 'Дополнительные языки' )->location( true );

		$repeat = add_field_repeat( 'languages' );
		$repeat->location()->ADMIN_MENUS( languages::$options_page_slug );
		$repeat->add_col_field( add_field_text( 'name' )->placeholder( 'Английский язык' ) )->label( 'Название языка' );
		$repeat->add_col_field( add_field_text( 'id' )->placeholder( 'en' ) )->label( 'ID языка' );
		$repeat->add_col_field( add_field_text( 'locale' )->placeholder( 'en_GB' ) )->label( 'Имя локалии языка' );
		$repeat->add_col_field( add_field_text( 'title' )->placeholder( 'english' ) )->label( 'Заголовок для смены языка' );
	}

	///POST TYPES
	add_action( 'wp_loaded', function(){
		add_field_separator( 'Список типов записей, для которых включить использование локалей' )->location()->ADMIN_MENUS( languages::$options_page_slug );
		$default_checked = array_flip( [ 'post', 'page' ] );
		foreach( languages::get_post_types( false ) as $post_type_name ){
			$post_type = get_post_type_object( $post_type_name );
			add_field_checkbox( 'post-type-' . $post_type_name )->label_checkbox( '<b>' . $post_type->label . '</b> (' . $post_type_name . ')' )->VALUE( array_key_exists( $post_type_name, $default_checked ) ? 'on' : '' )->get_parent_field()->location()->ADMIN_MENUS( languages::$options_page_slug );
		}
		///
		foreach( languages::get_post_types( true ) as $post_type ){
			$languages = [];
			foreach( languages::get_languages() as $lang ){
				$languages[ $lang->get_id() ] = $lang->get_name();
			}
			///META BOX
			add_action( 'add_meta_boxes', function( $post_type = null, $post = null ){

				add_meta_box( 'hiweb-metabox-side-default-ustanovki-lokalii', 'Установки локалии', function( $wp_post ){
					$post = languages::get_post( $wp_post->ID );
					$current_lang_id = get_post_meta( $wp_post->ID, languages::$post_meta_key_lang_id, true );
					if( trim( $current_lang_id ) == '' && array_key_exists( languages::$post_create_sibling_get_key_lang_id, $_GET ) ){
						$current_lang_id = $_GET[ languages::$post_create_sibling_get_key_lang_id ];
					} elseif( trim( $current_lang_id ) == '' ) {
						$current_lang_id = languages::get_default_id();
					}
					$sibling_posts = $post->get_sibling_posts( true );
					?>
					<p><label class="post-attributes-label">Текущая локализация</label></p>
					<?php
					if( languages\detect::is_multisite() ){
						?>
						<input disabled="disabled" value="<?= languages::get_current_language()->get_name() ?>">
						<?php
					} else {
						?>
						<select name="<?= languages::$post_meta_key_lang_id ?>">
							<?php
								foreach( languages::get_languages() as $language ){
									if( array_key_exists( $language->get_id(), $sibling_posts ) && $language->get_id() != $post->get_lang_id() ) continue;
									$selected = $language->get_id() == $current_lang_id;
									?>
									<option <?= $selected ? 'selected' : '' ?> value="<?= $language->get_id() ?>"><?= $language->get_name() ?> (<?= $language->is_default() ? 'станд.язык - ' : '' ?><?= $language->get_id() ?>)</option><?php
								} ?>
						</select>
						<?php

						if( get_current_screen()->action == '' ){
							?>
							<p><label class="post-attributes-label">Другие локалии статьи/страницы</label></p>
							<?php
							foreach( languages::get_languages() as $language ){
								if( $post->get_lang_id() != $language->get_id() ){
									$class = [ 'button' ];
									if( array_key_exists( $language->get_id(), $sibling_posts ) ){
										$href = get_edit_post_link( $sibling_posts[ $language->get_id() ]->get_post_id() );
										$title = 'Редактировать локализированную версию статьи/страницы';
										$text = '<i class="fas fa-file-edit"></i> Редакт: ';
									} else {
										$href = get_admin_url( null, '/post-new.php?post_type=' . $wp_post->post_type . '&' . languages::$post_create_sibling_get_key_id . '=' . $wp_post->ID . '&' . languages::$post_create_sibling_get_key_lang_id . '=' . $language->get_id() );
										$title = 'Создать новую локализированную статью/страницу';
										$class[] = 'button-primary';
										$text = '<i class="fas fa-file-alt"></i> Создать: ';
									}
									$text .= $language->get_name() . ' (' . $language->get_id() . ')';
									?>
									<p><a href="<?= $href ?>" title="<?= $title ?>" class="<?= join( ' ', $class ) ?>"><?= $text ?></a></p>
									<?php
								}
							}
							?>
							<p class="description">Создайте локализированную версию этой статьи/страницы или отредактируйте уже созданные, кликнув на соответствующую кнопку выше</p>
							<?php
						}
					}
				}, $post_type, 'side', 'high', [] );
			} );

			///POSTS LIST
			add_filter( 'views_edit-' . $post_type, function( $views ){
				foreach( languages::get_languages() as $language ){
					$views[ 'hiweb-language-lang-id-' . $language->get_id() ] = '<a href="' . \hiweb\PathsFactory::get()->set_params( [ 'lang' => $language->get_id() ] ) . '" ' . ( \hiweb\PathsFactory::request( 'lang' ) == $language->get_id() ? 'class="current"' : '' ) . ' aria-current="page">
					' . strtoupper( $language->get_id() ) . ' <span class="count">(0)</span>
					</a>';
				}
				return $views;
			} );
			if( !languages\detect::is_multisite() ){
				///COLUMNS MANAGER
				add_filter( "manage_{$post_type}_posts_columns", function( $posts_columns ){
					$posts_columns = \hiweb\arrays::get( $posts_columns )->push_value( 'Локализация', 4, languages::$post_columns_id );
					return $posts_columns;
				} );
				add_action( "manage_{$post_type}_posts_custom_column", function( $column_name, $post_id ){
					if( $column_name == languages::$post_columns_id ){
						$lang_post_current = languages::get_post( $post_id );
						echo '<div><b>' . $lang_post_current->get_language()->get_name() . ' (' . $lang_post_current->get_lang_id() . ')</b></div>';
						$sibling_posts = $lang_post_current->get_sibling_posts( true );
						foreach( $sibling_posts as $lang_id => $sibling_lang_post ){
							if( $lang_post_current->get_post_id() == $sibling_lang_post->get_post_id() ) continue;
							?><a style="font-size: 80%" href="<?= get_edit_post_link( $sibling_lang_post->get_post_id() ) ?>"><?= $lang_id ?>: <?= $sibling_lang_post->get_wp_post()->post_title ?></a> <?php
						}
					}
				}, 10, 2 );
			}

			////TERMS META
			foreach( get_object_taxonomies( $post_type ) as $taxonomy ){
				add_action( "{$taxonomy}_add_form_fields", function(){
					if( !languages\detect::is_multisite() ){
						?>
						<div class="form-field term-language-wrap">
							<label for="tag-description">Установки локализации</label>
							<select name="<?= languages::$post_meta_key_lang_id ?>">
								<?php
									foreach( languages::get_languages() as $language ){
										?>
										<option value="<?= $language->get_id() ?>"><?= $language->get_name() ?> (<?= $language->is_default() ? 'станд.язык - ' : '' ?><?= $language->get_id() ?>)</option><?php
									} ?>
							</select>
							<p></p>
						</div>
						<?php
					}
				} );

				add_action( "{$taxonomy}_edit_form", function( $wp_term, $taxonomy ){
					if( !$wp_term instanceof WP_Term || languages\detect::is_multisite() ) return;
					///
					?>
					<table class="form-table">
						<tr class="form-field term-description-wrap">
							<th scope="row"><label for="description">Установки локализации</label></th>
							<td>
								<select name="<?= languages::$post_meta_key_lang_id ?>">
									<?php
										foreach( languages::get_languages() as $language ){
											$selected = $language->get_id() == get_term_meta( $wp_term->term_id, languages::$post_meta_key_lang_id, true );
											?>
											<option <?= $selected ? 'selected' : '' ?> value="<?= $language->get_id() ?>"><?= $language->get_name() ?> (<?= $language->is_default() ? 'станд.язык - ' : '' ?><?= $language->get_id() ?>)</option><?php
										} ?>
								</select>
								<br>
								<br>
								<p>Создать/редактировать другие версии данного термина:</p>
								<?php
									$term = languages::get_term( $wp_term );
									$sibling_terms = $term->get_sibling_terms();
									foreach( languages::get_languages() as $language ){
										if( $term->get_lang_id() != $language->get_id() ){
											$class = [ 'button' ];
											if( $term->is_sibling_lang_exists( $language->get_id() ) ){
												$href = get_edit_term_link( $sibling_terms[ $language->get_id() ]->get_term_id() );
												$title = 'Редактировать локализированную версию';
												$text = '<i class="fas fa-file-edit"></i> Редакт: ';
											} else {
												$href = get_admin_url( null, 'edit-tags.php?taxonomy=' . $taxonomy . '&' . languages::$post_create_sibling_get_key_id . '=' . $wp_term->term_id . '&' . languages::$post_create_sibling_get_key_lang_id . '=' . $language->get_id() );
												$title = 'Создать новую локализированную версию';
												$class[] = 'button-primary';
												$text = '<i class="fas fa-file-alt"></i> Создать: ';
											}
											$text .= $language->get_name() . ' (' . $language->get_id() . ')';
											?>
											<p><a href="<?= $href ?>" title="<?= $title ?>" class="<?= join( ' ', $class ) ?>"><?= $text ?></a></p>
											<?php
										}
									}
								?>
								<p class="description">Создайте локализированную версию этого термина или отредактируйте уже созданные, кликнув на соответствующую кнопку выше</p>
							</td>
						</tr>
					</table>
					<?php
				}, 10, 2 );
			}
		}
	} );

	add_action( 'current_screen', function(){

		foreach( languages::get_post_types( true ) as $post_type ){
			foreach( get_object_taxonomies( $post_type ) as $taxonomy ){
				if( get_current_screen()->base == 'edit-tags' && get_current_screen()->id == "edit-{$taxonomy}" ){


					add_filter( "manage_edit-{$taxonomy}_columns", function( $columns ){
						$columns = \hiweb\arrays::push( $columns, 'Локализация', 2, languages::$post_columns_id );
						return $columns;
					}, 99, 3 );
					add_filter( "manage_{$taxonomy}_custom_column", function( $string, $column_name, $term_id ){
						$lang_term = languages::get_term( $term_id );
						if( $column_name == languages::$post_columns_id ){
							echo '<div><b>' . $lang_term->get_language()->get_name() . ' (' . $lang_term->get_lang_id() . ')</b></div>';
							foreach( $lang_term->get_sibling_terms() as $lang_id => $sibling_lang_term ){
								if( $lang_term->get_term_id() == $sibling_lang_term->get_term_id() ) continue;
								?><a style="font-size: 80%" href="<?= get_edit_term_link( $sibling_lang_term->get_term_id() ) ?>"><?= $lang_id ?>: <?= $sibling_lang_term->get_wp_term()->name ?></a> <?php
							}
						}
						return $string;
					}, 10, 3 );
				}
			}
		}
	} );