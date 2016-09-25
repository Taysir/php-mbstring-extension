<?php
/**
 * 多バイト文字列拡張用関数群
 *
 * @author Taysir of Rabiah <Taysir@users.noreply.github.com>
 * @package extension-functions
 * @version 0.0.1-dev
 */

/**
 * 多バイト文字列の指定文字数での分割
 * 
 * str_splitの多バイト文字列版
 *
 * @param string $string 分割対象文字列
 * @param integer $split_length 文字数、省略された場合1が使用される
 * @param string $encoding エンコーディング、省略された場合mb_internal_encodingが使用される
 * @return string[]|false string[]: 成功した場合, false: 失敗した場合
 * @since 0.0.1-dev
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

/**
 * 多バイト文字の数値変換
 *
 * ordの多バイト文字列版
 *
 * @param type $string 対象文字
 * @param type $encoding エンコーディング、省略された場合mb_internal_encodingが使用される
 * @return integer 数値化された文字
 * @since 0.0.1-dev
 */
function mb_ord($string, $encoding = null) {
    $_encoding = 2 > func_num_args() ? mb_internal_encoding() : $encoding;

    $character = mb_substr($string, 0, 1, $_encoding);
    $integer = 0;  
    foreach (str_split($character) as $byte) {
        $integer = $integer * 0x100 + ord($byte);
    }
    return $integer;
}

/**
 * 数値の多バイト文字変換
 *
 * chrの多バイト文字列版
 * 
 * @param integer $integer 対象数値
 * @param string $encoding エンコーディング、省略された場合mb_internal_encodingが使用される
 * @return string|false 
 * @since 0.0.1-dev
 */
function mb_chr($integer, $encoding = null) {
    $_encoding = 2 > func_num_args() ? mb_internal_encoding() : $encoding;

    $string = '';
    do {
        $string = chr($integer % 0x100) . $string;
        $integer = floor($integer / 0x100);
    } while (0 < $integer);
    return mb_convert_encoding($string, mb_internal_encoding(), $_encoding);
}
