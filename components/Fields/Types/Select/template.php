<?php
	
	/**
	 * @var \hiweb\components\Fields\Types\Select\Field_Select $this
	 */
	
	$options = $this->Options()->options();
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
	if( $this->Options()->multiple() ){
		$attributes->push( 'multiple', '' );
	}
	if( $this->Options()->placeholder() != '' ){
		$attributes->push( 'placeholder', $this->Options()->placeholder() );
	}
?>
<div class="hiweb-field-type-select">
	<select <?= $attributes->get_param_html_tags() ?>><?= $options_html ?></select>
</div>
