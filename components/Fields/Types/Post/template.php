<?php
	
	/**
	 * @var Field_Post $this
	 * @var array      $selected
	 */
	
	use hiweb\components\Fields\Types\Post\Field_Post;
	
	
	$attributes = new \hiweb\core\ArrayObject\ArrayObject();
	
	///
	$attributes->push( 'data-global-id', $this->global_id() );
	if( $this->Options()->multiple() ){
		$attributes->push( 'multiple', '' );
		$attributes->push( 'size', 1 );
		if( $name != '' ) $name .= '[]';
	}
	$attributes->push('placeholder', $this->Options()->placeholder());
	$attributes->push( 'name', $name );

?>
<div class="hiweb-field-type-post">
	<select <?= $attributes->get_param_html_tags() ?> data-options="<?= htmlentities( json_encode( [ 'post_type' => $this->Options()->post_type() ] ) ) ?>" data-value="<?= htmlentities( json_encode( $value ) ) ?>">
		<?php
			foreach( $selected as $val => $title ){
				?>
				<option value="<?= $val ?>" selected><?= $title ?></option>
				<?php
			}
		?>
	</select>
</div>
