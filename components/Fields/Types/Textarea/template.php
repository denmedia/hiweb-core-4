<?php
	
	use hiweb\components\Fields\Types\Textarea\Field_Textarea;
	
	
	/**
	 * @var Field_Textarea $this
	 */
	
	$attributes = \hiweb\core\ArrayObject\ArrayObject::get_instance( [] );
	$attributes->push( 'name', $name );
	if( intval( $this->options()->rows() ) > 0 ){
		$attributes->push( 'rows', $this->options()->rows() );
	}
	if( $this->options()->placeholder() != '' ){
		$attributes->push( 'placeholder', $this->options()->placeholder() );
	}
?>
<div class="hiweb-field-type-textarea">
	<textarea <?= $attributes->get_as_tag_attributes() ?>><?= $value ?></textarea>
</div>