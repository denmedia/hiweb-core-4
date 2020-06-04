<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:06
	 */
	
	namespace theme\forms\inputs;
	
	
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;
	use hiweb\core\Strings;
	
	
	class input{
		
		static $default_name = 'input';
		static $input_title = 'Новый инпут';
		static $input_type = '';
		public $data = [];
		
		
		static function init(){
			static::$input_type = Strings::sanitize_id( static::$input_title );
		}
		
		
		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			
			$parent_repeat_field->add_col_flex_field( self::$input_type, add_field_separator( self::$input_title, 'Для данного инпута нет изменяемых данных' ) );
		}
		
		
		/**
		 * @param      $key
		 * @param bool $htmlentities
		 * @return mixed|null|string
		 */
		public function get_data( $key, $htmlentities = true ){
			if( array_key_exists( $key, $this->data ) ){
				return $htmlentities ? htmlentities( $this->data[ $key ] ) : $this->data[ $key ];
			}
			return null;
		}
		
		
		/**
		 * @param $key
		 * @param $value
		 */
		public function set_data( $key, $value ){
			$this->data[ $key ] = $value;
		}
		
		
		/**
		 * @return mixed|null|string
		 */
		public function get_name(){
			return is_null( self::get_data( 'name' ) ) ? self::$default_name : self::get_data( 'name' );
		}
		
		
		/**
		 * @param             $tag_name
		 * @param null|string $data_name
		 * @param null|mixed  $value
		 * @return string
		 */
		public function get_tag_pair( $tag_name, $data_name = null, $value = null ){
			$tag_value = ( is_null( $value ) ? self::get_data( is_null( $data_name ) ? $tag_name : $data_name ) : htmlentities( $value ) );
			if( is_null( $tag_value ) ) return '';
			else return $tag_name . '="' . $tag_value . '"';
		}
		
		
		/**
		 * @return bool
		 */
		public function is_required(){
			return $this->get_data( 'require' ) == 'on';
		}
		
		
		/**
		 * @return bool
		 */
		public function is_required_empty_label(){
			return $this->get_data( 'label' ) == '' && $this->is_required();
		}
		
		
		/**
		 * @param string $submit_value
		 * @return bool
		 */
		public function is_required_validate( $submit_value = '' ){
			return $submit_value != '';
		}
		
		
		public function is_type_email(){
			return false;
		}
		
		
		public function the_prefix(){
			?>
			<div class="input-wrap input-wrap-<?= self::get_name() ?>">
			<?php
			if( isset( $this->data['icon'] ) && $this->data['icon'] != '' ){
				?>
				<i class="<?= $this->data['icon'] ?>"></i>
				<?php
			}
			if( self::get_data( 'label' ) != '' ){
				?>
				<label class="label"><?= self::get_data( 'label' ) ?> <?= $this->is_required() ? '<span class="required">*</span>' : '' ?></label>
				<?php
			}
			elseif( self::is_required_empty_label() ){
				?>
				<div class="required-empty-label">
				<?php
			}
		}
		
		
		public function the(){
			$this->the_prefix();
			?><input type="text" name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?> <?= self::is_required() ? 'data-required' : '' ?>/>
			<?php
			$this->the_sufix();
		}
		
		
		public function the_sufix(){
			?>
			<?= $this->get_require_error_message_html() ?>
			</div>
			<?php
			if( $this->is_required_empty_label() ){
				?>
				</div>
				<?php
			}
		}
		
		
		/**
		 * @param $value
		 * @return string
		 */
		public function get_email_html( $value ){
			$label = trim( $this->get_data( 'label' ) );
			if( $label == '' ) $label = $this->get_data( 'placeholder' );
			if( $label == '' ) $label = self::$default_name;
			return '<b>' . $label . ':</b> ' . $this->get_email_value( $value );
		}
		
		
		/**
		 * @param $value
		 * @return string
		 */
		public function get_email_value( $value ){
			return nl2br( trim( $value ) );
		}
		
		
		/**
		 * @return string
		 */
		public function get_require_error_message(){
			return self::get_data( 'require-message', false );
		}
		
		
		/**
		 * @return string
		 */
		public function get_require_error_message_html(){
			if( $this->is_required() && $this->get_require_error_message() ){
				return '<div class="require-error-message">' . $this->get_require_error_message() . '</div>';
			}
			return '';
		}
		
		
		/**
		 * Возвращает TRUE, если поле разрешено для отправки
		 * @return bool
		 */
		public function is_email_submit_enable(){
			return true;
		}
		
		
		/**
		 *
		 */
		public function ajax_html(){
			return [ 'success' => false, 'message' => 'для данного инпута нет HTML через AJAX' ];
		}
		
	}