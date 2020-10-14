<?php
	
	/**
	 * @var \hiweb\components\Fields\Types\Select\Field_Select $this
	 */
	
	$options = $this->options()->options();
	$value = $this->get_sanitize_admin_value( $value );
	if( !is_array( $options ) ) $options = [];
	$options_html = '';
	foreach( $options as $key => $val ){
		$selected = '';
		if( !is_null( $value ) && $key == $value ){
			$selected = 'selected';
		}
		$options_html .= '<option ' . $selected . ' value="' . htmlentities( $key, ENT_QUOTES, 'UTF-8' ) . '">' . $val . '</option>';
	}
	$attributes = new \hiweb\core\ArrayObject\ArrayObject();
	$attributes->push( 'name', $name );
	if( $this->options()->multiple() ){
		$attributes->push( 'multiple', '' );
	}
	if( $this->options()->placeholder() != '' ){
		$attributes->push( 'placeholder', $this->options()->placeholder() );
	}
?>
<div class="hiweb-field-type-select">
	<select <?= $attributes->get_as_tag_attributes() ?>><?= $options_html ?></select>
</div>
