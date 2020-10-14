<?php
	
	namespace hiweb\components\Fields\Types\Content;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\core\Paths\PathsFactory;
	
	
	class Field_Content extends Field{
		
		protected $options_class = '\hiweb\components\Fields\Types\Content\Field_Content_Options';
		
		static $default_settings = [
			'theme' => 'modern',
			'skin' => 'lightgray',
			'language' => 'ru',
			'formats' => [
				'alignleft' => [ [ 'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', 'styles' => [ 'textAlign' => 'left' ] ], [ 'selector' => 'img,table,dl.wp-caption', 'classes' => 'alignleft' ] ],
				'aligncenter' => [ [ 'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', 'styles' => [ 'textAlign' => 'center' ] ], [ 'selector' => 'img,table,dl.wp-caption', 'classes' => 'aligncenter' ] ],
				'alignright' => [ [ 'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', 'styles' => [ 'textAlign' => 'right' ] ], [ 'selector' => 'img,table,dl.wp-caption', 'classes' => 'alignright' ] ],
				'alignjustify' => [ [ 'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', 'styles' => [ 'textAlign' => 'justify' ] ], [ 'selector' => 'img,table,dl.wp-caption', 'classes' => 'alignjustify' ] ],
				'strikethrough' => [ 'inline' => 'del' ],
			],
			'relative_urls' => false,
			'remove_script_host' => false,
			'convert_urls' => false,
			'browser_spellcheck' => true,
			'fix_list_elements' => true,
			'entities' => '38,amp,60,lt,62,gt',
			'entity_encoding' => 'raw',
			'keep_styles' => false,
			'resize' => false,
			'menubar' => true,
			'branding' => false,
			'preview_styles' => 'font-family font-size font-weight font-style text-decoration text-transform',
			'end_container_on_empty_block' => true,
			'wpeditimage_html5_captions' => true,
			'wp_lang_attr' => 'ru-RU',
			'wp_keep_scroll_position' => false,
			'wp_shortcut_labels' => [
				"Heading 1" => "access1",
				"Heading 2" => "access2",
				"Heading 3" => "access3",
				"Heading 4" => "access4",
				"Heading 5" => "access5",
				"Heading 6" => "access6",
				"Paragraph" => "access7",
				"Blockquote" => "accessQ",
				"Underline" => "metaU",
				"Strikethrough" => "accessD",
				"Bold" => "metaB",
				"Italic" => "metaI",
				"Code" => "accessX",
				"Align center" => "accessC",
				"Align right" => "accessR",
				"Align left" => "accessL",
				"Justify" => "accessJ",
				"Cut" => "metaX",
				"Copy" => "metaC",
				"Paste" => "metaV",
				"Select all" => "metaA",
				"Undo" => "metaZ",
				"Redo" => "metaY",
				"Bullet list" => "accessU",
				"Numbered list" => "accessO",
				"Insert\/edit image" => "accessM",
				"Remove link" => "accessS",
				"Toolbar Toggle" => "accessZ",
				"Insert Read More tag" => "accessT",
				"Insert Page Break tag" => "accessP",
				"Distraction-free writing mode" => "accessW",
				"Keyboard Shortcuts" => "accessH"
			],
			'content_css' => HIWEB_URL_ASSETS . '/css/wp-default.min.css',
			'plugins' => "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview,-anchor,-table,-code",
			'wpautop' => true,
			'indent' => false,
			"toolbar1" => "formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,alignjustify,link,wp_more,spellchecker,dfw,wp_adv",
			"toolbar2" => "strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help,anchor,table",
			"toolbar3" => "",
			"toolbar4" => "",
			'wp_autoresize_on' => true,
			'add_unload_trigger' => false
		];
		
		
		static function _ajax_get_admin_html(){
			if( !array_key_exists( 'id', $_POST ) ) return wp_send_json( [ 'success' => false, 'message' => 'не передан ID поля' ] );
			if( !array_key_exists( 'name', $_POST ) ) return wp_send_json( [ 'success' => false, 'message' => 'не передан NAME поля' ] );
			if( !array_key_exists( 'rand_id', $_POST ) ) return wp_send_json( [ 'success' => false, 'message' => 'не передан RAND_ID поля' ] );
			$id = stripslashes( $_POST['id'] );
			$global_id = stripslashes( $_POST['global_id'] );
			$rand_id = stripslashes( $_POST['rand_id'] );
			$name = stripslashes( $_POST['name'] );
			$value = html_entity_decode( stripslashes( $_POST['value'] ) );
			$Field = FieldsFactory::get_field( $global_id );
			ob_start();
			include __DIR__ . '/template-editor.php';
			return wp_send_json( [ 'success' => true, 'html' => ob_get_clean() ] );
		}
		
		
		public function __construct( $field_ID ){
			parent::__construct( $field_ID );
			static $footer_printed = false;
			if( !$footer_printed ){
				$footer_printed = true;
				$css = [ HIWEB_URL_ASSETS . '/css/wp-default.min.css', PathsFactory::get( WPINC . '/js/tinymce/skins/wordpress/wp-content.css' )->url()->get(), PathsFactory::get( __DIR__ . '/content.css' )->url()->get() ];
				self::$default_settings['content_css'] = join( ',', $css );
				add_action( 'in_admin_footer', function(){
					add_filter( 'mce_external_plugins', function( $plugins ){
						return $plugins;
					}, 9999 );
					include __DIR__ . '/template-footer-script.php';
				}, 999999 );
			}
		}
		
		
		public function admin_init(){
			parent::admin_init();
			wp_enqueue_media();
		}
		
		
		/**
		 * @return Field_Content_Options|mixed
		 */
		public function options(){
			return parent::options(); // TODO: Change the autogenerated stub
		}
		
		
		public function get_js(){
			return [ 'wp-tinymce', __DIR__ . '/tinymce-language-ru.min.js', HIWEB_DIR_COMPONENTS . '/TinyMCE/plugins/anchor/plugin.min.js', HIWEB_DIR_COMPONENTS . '/TinyMCE/plugins/table/plugin.min.js', HIWEB_DIR_COMPONENTS . '/TinyMCE/plugins/code/plugin.min.js', __DIR__ . '/Field_Content.min.js' ];
		}
		
		
		public function get_css(){
			return [ WPINC . '/css/editor.min.css', __DIR__ . '/Field_Content.css' ];
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
	}