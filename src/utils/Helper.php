<?php
namespace ymi\utils;

/**
 * Class with helper methods
 */
class Helper {

    /**
     * delete fileds defined by $criteria from $array
     *
     * @param array $array
     * @param array $criteria
     */
    public static function sanitizeUsersArray( $array, $criteria) {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                array_push($result, Helper::sanitizeUsersArray($value, $criteria));
                continue;
            }
            if (!in_array($key, $criteria)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * check if all fields from $criteria are in $array
     *
     * @param array $array
     * @param array $criteria
     */
    public static function isValid( $array,  $criteria) {
        foreach ($criteria as $item) {
            if(!isset($array[$item])) {
                return false;
            }
        }
        return true;
    }

    /**
     * delete empty values from array
     *
     * @param array $array
     */
    public static function clearEmpty($array) {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                array_push($result, Helper::clearEmpty($value));
                continue;
            }
            if ($value != "") {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * validates email
     *
     * @param string $email
     */
    public static function validateEmail($email) {
        return (bool)preg_match("/^[a-z0-9](\.?[a-z0-9_-]){0,}@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/", $email);
    }
}
