<?php
	
	use hiweb\components\Fields\Types\Text\Field_Text;
	
	
	/**
	 * @var Field_Text $this
	 */
	
	$attributes = \hiweb\core\ArrayObject\ArrayObject::get_instance(['type' => 'text']);
	if( $this->Options()->placeholder() != '' ){
		$attributes->push( 'placeholder', $this->Options()->placeholder() );
	}
	if( $this->get_sanitize_admin_value( $value ) != '' ){
		$attributes->push( 'value', $this->get_sanitize_admin_value( $value ) );
	}
	$attributes->push( 'name', $this->get_sanitize_admin_name( $name ) );

?>
<div class="hiweb-field-type-text"><input <?=$attributes->get_param_html_tags()?>/></div>