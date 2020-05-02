<?php
	
	use hiweb\components\Fields\Types\Textarea\Field_Textarea;
	
	
	/**
	 * @var Field_Textarea $this
	 */
	
	$attributes = \hiweb\core\ArrayObject\ArrayObject::get_instance( [] );
	$attributes->push( 'name', $name );
	if( intval( $this->Options()->rows() ) > 0 ){
		$attributes->push( 'rows', $this->Options()->rows() );
	}
	if( $this->Options()->placeholder() != '' ){
		$attributes->push( 'placeholder', $this->Options()->placeholder() );
	}
?>
<div class="hiweb-field-type-textarea">
	<textarea <?= $attributes->get_param_html_tags() ?>><?= $value ?></textarea>
</div>