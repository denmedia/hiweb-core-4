<?php
	
	use hiweb\components\Fields\Types\Content\Field_Content;
	use hiweb\core\Paths\PathsFactory;
	
	
	require_once PathsFactory::root()->get_absolute_path() . '/wp-includes/class-wp-editor.php';
	
	if( defined( 'WPINC' ) ){
		?>
		<script>
            if (typeof tinyMCEPreInit === 'undefined') {
                tinyMCEPreInit = {
                    baseURL: "<?=PathsFactory::get( WPINC )->get_url( false )?>/js/tinymce",
                    suffix: ".min",
                    ref: {plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview,anchor", theme: "modern", language: "en"},
                    load_ext: function (url, lang) {
                        var sl = tinymce.ScriptLoader;
                        sl.markDone(url + '/langs/' + lang + '.js');
                        sl.markDone(url + '/langs/' + lang + '_dlg.js');
                    }
                }
            }
            hiweb_fields_type_content_default_settings = <?= json_encode( _WP_Editors::parse_settings( 'hiweb-test', Field_Content::$default_settings ) ); ?>;
		</script>
		<?php
	}