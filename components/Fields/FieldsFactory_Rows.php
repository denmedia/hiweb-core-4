<?php

namespace hiweb\components\Fields;


use hiweb\components\Structures\StructuresFactory;
use hiweb\core\ArrayObject\ArrayObject_Rows;
use hiweb\core\hidden_methods;


/**
 * Class FieldsFactory_Rows
 * @package hiweb\components\Fields
 */
class FieldsFactory_Rows {

    use hidden_methods;


    /** @var ArrayObject_Rows[] */
    private static $queueRowsArray = [];
    /** @var ArrayObject_Rows */
    private static $currentRows;
    /** @var string */
    private static $currentRowsId;
    ///Sub Rows
    /** @var ArrayObject_Rows */
    private static $currentSubRows;
    /** @var string */
    private static $currentSubRowsId;

    private static $limit_rows = 999;


    /**
     * @param      $fieldId
     * @param null $objectContent
     * @return string
     */
    private static function get_rowId_byField($fieldId, $objectContent = null): string {
        return StructuresFactory::get_id_from_object(FieldsFactory::get_sanitize_objectContext($objectContent)) . ':' . $fieldId;
    }


    /**
     * @return ArrayObject_Rows
     */
    private static function get_dummy_row(): ArrayObject_Rows {
        return get_cache(__FUNCTION__, __CLASS__, function() {
            return get_array()->rows();
        })->get_value();
    }


    /**
     * @param bool $reverse
     * @return ArrayObject_Rows[]
     */
    static function get_queue_rows_array($reverse = true): array {
        $rowsArray = $reverse ? array_reverse(self::$queueRowsArray) : self::$queueRowsArray;
        if (self::$currentSubRows instanceof ArrayObject_Rows) $rowsArray = array_merge([self::$currentSubRowsId => self::$currentSubRows], $rowsArray);
        return $rowsArray;
    }


    /**
     * @return ArrayObject_Rows
     */
    static function get_current(): ArrayObject_Rows {
        if(self::$currentSubRows instanceof ArrayObject_Rows){
            return self::$currentSubRows;
        }
        if (count(self::$queueRowsArray) > 0) return end(self::$queueRowsArray); else return self::get_dummy_row();
    }


    /**
     * @param      $fieldId
     * @param null $contentObject
     * @return bool
     */
    static private function is_field_value_array($fieldId, $contentObject = null): bool {
        $fieldValue = FieldsFactory_FrontEnd::get_value($fieldId, $contentObject);
        return (is_array($fieldValue) && count($fieldValue) > 0);
    }


    /**
     * Return $rowId
     * @param      $fieldId
     * @param null $contextObject
     * @return string
     */
    static private function set_current_from_field($fieldId, $contextObject = null): string {
        $fieldValue = FieldsFactory_FrontEnd::get_value($fieldId, $contextObject);
        $rowId = self::get_rowId_byField($fieldId, $contextObject);
        if ( !array_key_exists($rowId, self::$queueRowsArray)) {
            self::$currentRows = get_array($fieldValue)->rows();
            self::$queueRowsArray[$rowId] = self::$currentRows;
            self::$currentRowsId = $rowId;
            self::$currentSubRows = null;
            self::$currentSubRowsId = null;
        }
        self::clear_queue_from_row($rowId);
        return $rowId;
    }


    /**
     * @param $rowId
     * @return bool
     */
    static private function clear_queue_from_row($rowId): bool {
        if ( !array_key_exists($rowId, self::$queueRowsArray)) return false;
        foreach (array_reverse(self::$queueRowsArray) as $_rowId => $_row) {
            if ($_rowId == $rowId) {
                break;
            }
            unset(self::$queueRowsArray[$_rowId]);
        }
        self::$currentRows = end(self::$queueRowsArray);
        self::$currentRowsId = key(self::$queueRowsArray);
        return true;
    }


    static function have($fieldId, $contextObject = null): bool {
        if(self::$limit_rows < 0) return false;
        self::$limit_rows --;
        if ( !self::is_field_value_array($fieldId, $contextObject)) return false;
        $rowId = self::set_current_from_field($fieldId, $contextObject);
        if (self::$queueRowsArray[$rowId]->have()) {
            return true;
        } else {
            unset(self::$queueRowsArray[$rowId]);
        }
        return false;
    }


    /**
     * @param      $fieldId
     * @param null $contextObject
     * @return int
     */
    static function reset($fieldId, $contextObject = null): int {
        if ( !self::is_field_value_array($fieldId, $contextObject)) return - 1;
        $rowId = self::set_current_from_field($fieldId, $contextObject);
        self::clear_queue_from_row($rowId);
        return self::$currentRows->reset();
    }


    /**
     * @return mixed|null
     */
    static function the() {
        return self::get_current()->the();
    }


    /**
     * @param string $subFieldId
     * @param null   $default
     * @return mixed|null
     */
    static function get_sub_field(string $subFieldId, $default = null) {
        foreach (self::get_queue_rows_array() as $rowId => $rows) {
            if ($rows->have_sub_field($subFieldId)) {
                return $rows->get_sub_field($subFieldId, $default);
            }
        }
        return $default;
    }


    /**
     * @param string $subFieldId
     * @param null   $default
     * @return mixed|null
     */
    static function get_parent_field(string $subFieldId, $default = null) {
        $first = true;
        foreach (self::get_queue_rows_array() as $rowId => $rows) {
            if ( !$first && $rows->have_sub_field($subFieldId)) {
                return $rows->get_sub_field($subFieldId, $default);
            }
            $first = false;
        }
        return $default;
    }


    ///Sub Rows


    /**
     * @param $subFieldId
     * @return bool
     */
    static function have_sub_rows($subFieldId): bool {
        if(self::$limit_rows < 0) return false;
        self::$limit_rows --;
        if (count(self::$queueRowsArray) == 0) return false;
        $subFieldValue = self::get_sub_field($subFieldId);
        if ( !is_array($subFieldValue) || count($subFieldValue) == 0) return false;
        $rowId = self::$currentRowsId . ':' . $subFieldId.':'.self::$currentRows->get_index();
        if (self::$currentSubRowsId != $rowId || !self::$currentSubRows instanceof ArrayObject_Rows) {
            self::$currentSubRowsId = $rowId;
            self::$currentSubRows = get_array($subFieldValue)->rows();
        }
        $have = self::$currentSubRows->have();
        if(!$have) {
            self::$currentSubRowsId = null;
            self::$currentSubRows = null;
        }
        return $have;
    }

}