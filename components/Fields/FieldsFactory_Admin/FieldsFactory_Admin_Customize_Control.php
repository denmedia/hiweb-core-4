<?php

namespace hiweb\components\Fields\FieldsFactory_Admin;


use hiweb\components\Fields\Field;
use hiweb\core\Strings;
use WP_Customize_Control;
use WP_Customize_Manager;


/**
 * Helper class for outputting a field in Theme Customize Page
 *
 * @package hiweb\components\Fields\Field_Options
 */
class FieldsFactory_Admin_Customize_Control extends WP_Customize_Control {

	/** @var Field */
	private $parentField;


	/**
	 * theme constructor.
	 *
	 * @param Field                $field
	 * @param WP_Customize_Manager $manager
	 * @param null|string          $section_id
	 */
	public function __construct(Field $field, WP_Customize_Manager $manager, $section_id = null) {
		$this->parentField = $field;
		parent::__construct(
			$manager, $field->id(), array(
			'settings' => $field->id(),
			'section'  => is_string( $section_id ) ? $section_id : Strings::sanitize_id( $field->options()->location()->customize()->section()->title() ),
		)
		);
	}


	public function render_content() {
		if ( !$this->parentField instanceof field ) {
			//console_warn( 'Ошибка рендера инпута для секции настроек темы' );
		} else {
			//			$this->input->attributes['id'] = '_customize-input-' . $this->parentField->id();
			//			$this->input->attributes['data-customize-setting-link'] = $this->parentField->id();
			//			unset( $this->input->attributes['name'] );
			if ( $this->parentField->options()->form()->show_labels() ) {
				?><label for="<?= $this->parentField->id() ?>"><span class="customize-control-title"><?= $this->parentField->options()->label() ?></span><span
						class="description customize-control-description"><?= $this->parentField->options()->description() ?></span><?php
			}
			///
			echo $this->parentField->get_admin_html( $this->value() );
			///
			if ( $this->parentField->options()->form()->show_labels() ) {
				?>
				</label>
				<?php
			}
		}
	}

}