<?php

namespace App;

trait Validators {

    /**
     * check for positive integer
     *
     * @mvc Controller
     *
     * @param  int $input
     * @return boolean
     */

    public static function is_pos_int ($input) {
        if (!is_numeric($input) || $input < 1 || $input != round($input)) {
            return false;
        }
        return true;
    }

    /**
     * check for none empty string
     *
     * @mvc Controller
     *
     * @param  string $value
     * @return boolean
     */

    public static function none_empty_string ($value) {
       return strlen($value) > 0 ? true : false;
    }

    /**
     * check if string starts with a substring
     *
     * @mvc Controller
     *
     * @param  string $str
     * @param  string $sub_str
     * @return boolean
     */

    public static function string_start_with ($str, $sub_str) {
        return strpos($str, $sub_str) === 0 ? true : false;
    }

    /**
     * check if array is not empty
     *
     * @mvc Controller
     *
     * @param  array $input
     * @return boolean
     */

    public static function none_empty_array ($input) {
        return (is_array($input) && !empty($input)) ? true : false;
    }

    /**
     * check if value is a boolean
     *
     * @mvc Controller
     *
     * @param  array $input
     * @return boolean
     */

    public static function is_boolean ($input) {
        return is_bool($input) ? true : false;
    }

    /**
     * check if value is a valid email
     *
     * @mvc Controller
     *
     * @param  array $input
     * @return boolean
     */

    public static function valid_email ($input) {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false ? true : false;
    }

    /**
     * check if value is int between 1-100
     *
     * @mvc Controller
     *
     * @param  array $input
     * @return boolean
     */

    public static function percentage_value ($input) {
        if (is_numeric($input) && $input >= 0 && $input <= 100 && $input == round($input)) {
            return true;
        }
        return false;
    }
}