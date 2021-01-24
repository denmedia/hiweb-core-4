<?php

namespace hiweb\components\Fields\Types\Repeat;


use hiweb\components\Fields\Field;
use hiweb\components\Fields\Field_Options;
use hiweb\components\Fields\FieldsFactory;
use hiweb\core\Options\Options;


class Field_Repeat_Col extends Options {

    /** @var Field */
    private $Field;
    /** @var Field */
    private $repeat_Field;
    /** @var Field_Repeat_Flex */
    private $Flex;


    /**
     * @param Field               $repeat_Field
     * @param Field|Field_Options $Field_or_FieldOptions
     * @param null                $Flex
     * @version 1.1
     */
    public function __construct(Field $repeat_Field, $Field_or_FieldOptions, $Flex = null) {
        $this->repeat_Field = $repeat_Field;
        if ($Flex instanceof Field_Repeat_Flex) $this->Flex = $Flex; else $this->Flex = new Field_Repeat_Flex($this->Field, '');
        if ($Field_or_FieldOptions instanceof Field) {
            $this->Field = $Field_or_FieldOptions;
        } elseif ($Field_or_FieldOptions instanceof Field_Options) {
            $this->Field = $Field_or_FieldOptions->field();
        } else {
            $this->Field = FieldsFactory::get_field('');
        }
        parent::__construct($this->Field->options());
    }


    /**
     * @return bool
     */
    protected function is_exist(): bool {
        return $this->Field instanceof Field;
    }


    /**
     * @return null|string
     */
    public function get_id(): ?string {
        return $this->is_exist() ? $this->Field->id() : null;
    }


    /**
     * @alias $this->get_id()
     * @return null|string
     */
    public function ID(): ?string {
        return $this->get_id();
    }


    /**
     * @return Field|Field_Options
     */
    public function field() {
        return $this->is_exist() ? $this->Field : FieldsFactory::get_field('');
    }


    /**
     * @return Field_Repeat_Flex
     */
    public function flex(): Field_Repeat_Flex {
        return $this->Flex;
    }


    /**
     * @param null|string $set
     * @return array|Field_Repeat_Col|mixed|null
     */
    public function label($set = null) {
        return $this->_('label', $set);
    }


    /**
     * @param null|string $set
     * @return array|Field_Repeat_Col|mixed|null
     */
    public function description($set = null) {
        return $this->_('description', $set);
    }


    /**
     * Set col width: 0 - 10 => flex-grow:... | 11 - 100 => flex-basis: ...% | > 101 => flex-basis: ...px | 50px | 50%
     * @param null|int $set
     * @return array|Field_Repeat_Col|mixed|null
     */
    public function width($set = null) {
        return $this->_('width', $set, 1);
    }


    /**
     * Set col compact mod
     * @param null|bool|int $set
     * @return array|Field_Repeat_Col|mixed|null
     */
    public function compact($set = null) {
        return $this->_('compact', $set);
    }


    /**
     * @param null $set
     * @return array|Field_Repeat_Col|mixed|null
     */
    public function hidden($set = null) {
        return $this->_('hide', $set, false);
    }

}