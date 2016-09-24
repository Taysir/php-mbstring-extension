<?php

/**
 * 
 * @param string $string
 * @param integer $split_length
 * @param string $encoding
 * @return array | false
 */
function mb_str_split($string, $split_length = 1, $encoding = null) {
    $_split_length = (integer) $split_length;
    if (1 > $_split_length) {
        trigger_error('mb_str_split(): The length of each segment must be greater than zero', E_USER_WARNING);
        return false;
    }
    $_encoding = 3 > func_num_args() ? mb_internal_encoding() : $encoding; 

    $_mb_strlen = mb_strlen($string, $_encoding);

    if (strlen($string) === $_mb_strlen) {
        return str_split($string, $_split_length);
    }

    if ($_split_length >= $_mb_strlen) {
        return [$string];
    }

    $segments = [];
    foreach (range(0, $_mb_strlen - 1, $_split_length) as $start) {
        $segments[] = mb_substr($string, $start, $_split_length, $_encoding);
    }
    return $segments;

}

function mb_ord($string, $encoding = null) {
    $_encoding = 2 > func_num_args() ? mb_internal_encoding() : $encoding;

    $character = mb_substr($string, 0, 1, $_encoding);
    $integer = 0;  
    foreach (str_split($character) as $byte) {
        $integer = $integer * 0x100 + ord($byte);
    }
    return $integer;
}

function mb_chr($integer, $encoding = null) {
    $_encoding = 2 > func_num_args() ? mb_internal_encoding() : $encoding;

    $string = '';
    do {
        $string = chr($integer % 0x100) . $string;
        $integer = floor($integer / 0x100);
    } while (0 < $integer);
    return mb_convert_encoding($string, mb_internal_encoding(), $_encoding);
}
