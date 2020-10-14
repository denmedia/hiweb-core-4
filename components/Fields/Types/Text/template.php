<?php
	
	use hiweb\components\Fields\Types\Text\Field_Text;
	
	
	/**
	 * @var Field_Text $this
	 */
	
	$attributes = \hiweb\core\ArrayObject\ArrayObject::get_instance( [ 'type' => 'text' ] );
	if( $this->options()->placeholder() != '' ){
		$attributes->push( 'placeholder', $this->options()->placeholder() );
	}
	if( $this->get_sanitize_admin_value( $value ) != '' ){
		$attributes->push( 'value', $this->get_sanitize_admin_value( $value ) );
	}
	$attributes->push( 'name', $this->get_sanitize_admin_name( $name ) );
	
	$font_size = floatval( $this->options()->font_size() );
	if( $font_size <= 0 ) $font_size = 1;
	if($font_size != 1) {
		$attributes->push('style', 'font-size: '.$font_size.'em;');
	}
?>
<div class="hiweb-field-type-text"><input <?= $attributes->get_as_tag_attributes() ?>/></div>