<?php
	
	/**
	 * @var \hiweb\components\Fields\Types\Terms\Field_Terms $this
	 */
	
	$terms = $this->get_terms_by_taxonomy();
	$attributes = new \hiweb\core\ArrayObject\ArrayObject();
	if($this->Options()->placeholder() != '') {
		$attributes->push( 'placeholder', $this->Options()->placeholder() );
	}
	if( $this->Options()->multiple() ){
		$attributes->push( 'multiple', '' );
		$attributes->push( 'size', 1 );
		if( $name != '' ) $name .= '[]';
	}
	$attributes->push( 'name', $name );

?>
<div class="hiweb-field-type-terms">
	<select <?= $attributes->get_param_html_tags() ?>>
		<?php
			$this->get_html_options_from_terms( $value, $terms );
		?>
	</select>
</div>
