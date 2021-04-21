<?php

if (!function_exists('array_get_first_key')) {
    function array_get_first_key($array) {
        $key = array_keys($array);
        $key = reset($key);
        return $key;
    }
}

if(!function_exists('array_replace')) {
    function array_replace() {
        $args = func_get_args();
        $num_args = func_num_args();
        $res = array();
        for($i=0; $i<$num_args; $i++) {
            if(is_array($args[$i])) {
                foreach($args[$i] as $key => $val) {
                    $res[$key] = $val;
                }
            }
            else {
                trigger_error(__FUNCTION__ .'(): Argument #'.($i+1).' is not an array', E_USER_WARNING);
                return NULL;
            }
        }
        return $res;
    }
}

class SoUtils {
    public static function getProperty($array, $property, $default_value = null) {
        $properties = explode('.', $property);
        foreach ($properties as $prop) {
            if (!is_array($array) || !isset($array[$prop])) {
                return $default_value;
            }
            $array = $array[$prop];
        }
        if (is_array($array)) {
            return $array;
        }
        $array = trim($array);
        return $array !== '' ? $array : $default_value;
    }
}